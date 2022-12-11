<?php

namespace App\Controller;

use App\Entity\StudentGrade;
use App\Form\StudentGradeType;
use App\Repository\StudentGradeRepository;
use App\Repository\StudentRepository;
use App\Repository\GradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentGradeController extends AbstractController
{
    private $studentGradeRepository;
    private $gradeRepository;
    private $studentRepository;

    public function __construct(StudentGradeRepository $studentGradeRepository, GradeRepository $gradeRepository, StudentRepository $studentRepository)
    {
        $this->studentGradeRepository = $studentGradeRepository;
        $this->gradeRepository = $gradeRepository;
        $this->studentRepository = $studentRepository;
    }

    #[Route('/{schoolId}/grade/{gradeId}/studentGrades/{studentId}/new', name: 'studentGrade_new')]
    public function new(Request $request, int $schoolId, int $gradeId, int $studentId): Response
    {
        $studentGrade = new StudentGrade();
        $studentGrade->setStudent($this->studentRepository->find($studentId));
        $studentGrade->setGrade($this->gradeRepository->find($gradeId));
        $form = $this->createForm(StudentGradeType::class, $studentGrade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->studentGradeRepository->save($studentGrade, true);

            return $this->redirectToRoute('grade_show', ['schoolId' => $schoolId, 'gradeId' => $gradeId]);
        }

        return $this->render('studentGrade/new.html.twig', [
            'studentGrade' => $studentGrade,
            'form' => $form->createView(),
            'activePage' => 'grade',
            'schoolId' => $schoolId,
            'gradeId' => $gradeId,
        ]);
    }

    #[Route('/{schoolId}/grade/{gradeId}/studentGrades/{studentId}/edit', name: 'studentGrade_edit')]
    public function edit(Request $request, int $schoolId, int $gradeId, int $studentId): Response
    {
        $studentGrade = $this->studentGradeRepository->findOneBy(['student' => $studentId, 'grade' => $gradeId]);
        $form = $this->createForm(StudentGradeType::class, $studentGrade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->studentGradeRepository->save($studentGrade, true);

            return $this->redirectToRoute('grade_show', ['schoolId' => $schoolId, 'gradeId' => $gradeId]);
        }

        return $this->render('studentGrade/edit.html.twig', [
            'studentGrade' => $studentGrade,
            'form' => $form->createView(),
            'activePage' => 'grade',
            'schoolId' => $schoolId,
            'gradeId' => $gradeId,
        ]);
    }
}
