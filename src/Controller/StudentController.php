<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Form\AddressType;
use App\Form\ClasseType;
use App\Repository\StudentRepository;
use App\Repository\StudentGradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends AbstractController
{
    private StudentRepository $studentRepository;
    private StudentGradeRepository $studentGradeRepository;

    public function __construct(StudentRepository $studentRepository, StudentGradeRepository $studentGradeRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->studentGradeRepository = $studentGradeRepository;
    }

    #[Route('/{schoolId}/student', name: 'student_index')]
    public function index(int $schoolId): Response
    {
        $search = $_GET['search'] ?? null;
        $searchMode = $_GET['searchMode'] ?? null;
        if ($search && ($searchMode === 'firstname' || $searchMode === 'lastname' || $searchMode === 'email')) {
            $students = $this->studentRepository->findBySearchMode($search, $searchMode);
        } else {
            $students = $this->studentRepository->findAllBySchoolId($schoolId);
        }

        return $this->render('student/index.html.twig', [
            'students' => $students,
            'activePage' => 'student',
            'schoolId' => $schoolId,
            'search' => $search,
            'searchMode' => $searchMode,
        ]);
    }

    #[Route('/{schoolId}/student/{studentId}', name: 'student_show')]
    public function show(int $schoolId, int $studentId): Response
    {
        $student = $this->studentRepository->find($studentId);
        $studentGrades = $this->studentGradeRepository->findAllByStudentId($studentId);
        $totalGrade = 0;

        foreach ($studentGrades as $studentGrade) {
            $totalGrade += $studentGrade->getScore();
        }

        if (sizeof($studentGrades) !== 0) {
            $studentAverage = $totalGrade/sizeof($studentGrades) . '/20';
        } else {
            $studentAverage = 'No grades yet';
        }

        return $this->render('student/show.html.twig', [
            'student' => $student,
            'activePage' => 'student',
            'schoolId' => $schoolId,
            'studentAverage' => $studentAverage,
        ]);
    }

    #[Route('/{schoolId}/student/{studentId}/edit', name: 'student_edit')]
    public function edit(Request $request, int $schoolId, int $studentId): Response
    {
        $student = $this->studentRepository->find($studentId);
        $options = [
            'schoolId' => $schoolId,
        ];
        $form = $this->createForm(StudentType::class, $student, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->studentRepository->save($student, true);

            return $this->redirectToRoute('student_index', [
                'schoolId' => $schoolId,
            ]);
        }

        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'student',
            'schoolId' => $schoolId,
        ]);
    }
    
    #[Route('/{schoolId}/student-add', name: 'student_new')]
    public function new(Request $request, int $schoolId): Response
    {
        $student = new Student();
        $options = [
            'schoolId' => $schoolId,
        ];
        $form = $this->createForm(StudentType::class, $student, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->studentRepository->save($student, true);

            return $this->redirectToRoute('student_index', [
                'schoolId' => $schoolId,
            ]);
        }

        return $this->render('student/new.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'student',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/student/{studentId}/delete', name: 'student_delete')]
    public function delete(int $schoolId, int $studentId): Response
    {
        $this->studentRepository->delete($studentId);

        return $this->redirectToRoute('student_index', [
            'schoolId' => $schoolId,
        ]);
    }
}
