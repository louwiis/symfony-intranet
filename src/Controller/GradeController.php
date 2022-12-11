<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Form\GradeType;
use App\Repository\GradeRepository;
use App\Repository\StudentGradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GradeController extends AbstractController
{
    private GradeRepository $gradeRepository;
    private StudentGradeRepository $studentGradeRepository;

    public function __construct(GradeRepository $gradeRepository, StudentGradeRepository $studentGradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
        $this->studentGradeRepository = $studentGradeRepository;
    }

    #[Route('/{schoolId}/grade', name: 'grade_index')]
    public function index(int $schoolId): Response
    {
        $grades = $this->gradeRepository->findAllBySchoolId($schoolId);

        return $this->render('grade/index.html.twig', [
            'grades' => $grades,
            'activePage' => 'grade',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/grade/{gradeId}', name: 'grade_show')]
    public function show(int $schoolId, int $gradeId): Response
    {
        $grade = $this->gradeRepository->find($gradeId);

        $studentGrades = [];
        $totalGrade = 0;
        $numberOfStudents = 0;
        foreach ($grade->getClasse()->getStudents() as $student) {
            $studentGrade = $this->studentGradeRepository->findOneBy(['student' => $student, 'grade' => $grade]);
            if ($studentGrade) {
                $studentGrades[] = [
                    'student' => $studentGrade->getStudent(),
                    'score' => $studentGrade->getScore(),
                ];

                $totalGrade += $studentGrade->getScore();
                $numberOfStudents++;
            } else {
                $studentGrades[] = [
                    'student' => $student,
                    'score' => null,
                ];
            }
        }

        if ($numberOfStudents > 0) {
            $studentsAverage = $totalGrade/$numberOfStudents . '/20';
        } else {
            $studentsAverage = 'No grades yet';
        }
        
        return $this->render('grade/show.html.twig', [
            'grade' => $grade,
            'activePage' => 'grade',
            'schoolId' => $schoolId,
            'studentGrades' => $studentGrades,
            'studentsAverage' => $studentsAverage,
        ]);
    }

    #[Route('/{schoolId}/grade/{gradeId}/edit', name: 'grade_edit')]
    public function edit(Request $request, int $schoolId, int $gradeId): Response
    {
        $grade = $this->gradeRepository->find($gradeId);
        $options = ['schoolId' => $schoolId];
        $form = $this->createForm(GradeType::class, $grade, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->gradeRepository->save($grade, true);

            return $this->redirectToRoute('grade_index', ['schoolId' => $schoolId]);
        }

        return $this->render('grade/edit.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'grade',
            'schoolId' => $schoolId,
        ]);
    }
    
    #[Route('/{schoolId}/grade-add', name: 'grade_new')]
    public function new(Request $request, int $schoolId): Response
    {
        $grade = new Grade();
        $options = ['schoolId' => $schoolId];
        $form = $this->createForm(GradeType::class, $grade, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->gradeRepository->save($grade, true);

            return $this->redirectToRoute('grade_show', ['schoolId' => $schoolId, 'gradeId' => $grade->getId()]);
        }

        return $this->render('grade/new.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'grade',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/grade/{gradeId}/delete', name: 'grade_delete')]
    public function delete(int $schoolId, int $gradeId): Response
    {
        $this->gradeRepository->delete($gradeId);

        return $this->redirectToRoute('grade_index', ['schoolId' => $schoolId]);
    }
}
    