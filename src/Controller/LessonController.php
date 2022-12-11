<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    private LessonRepository $lessonRepository;

    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    #[Route('/{schoolId}/lesson', name: 'lesson_index')]
    public function index(int $schoolId): Response
    {
        $search = $_GET['search'] ?? null;
        $searchMode = $_GET['searchMode'] ?? null;
        if ($search && ($searchMode === 'name' || $searchMode === 'teacher' || $searchMode === 'room')) {
            $lessons = $this->lessonRepository->findBySearchMode($search, $searchMode);
        } else {
            $lessons = $this->lessonRepository->findAllBySchoolId($schoolId);
        }

        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
            'activePage' => 'lesson',
            'schoolId' => $schoolId,
            'search' => $search,
            'searchMode' => $searchMode,
        ]);
    }

    #[Route('/{schoolId}/lesson/new', name: 'lesson_new')]
    public function new(Request $request, int $schoolId): Response
    {
        $lesson = new Lesson();
        $options = [
            'schoolId' => $schoolId,
        ];
        $form = $this->createForm(LessonType::class, $lesson, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->lessonRepository->save($lesson, true);

            return $this->redirectToRoute('lesson_index', ['schoolId' => $schoolId]);
        }

        return $this->render('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
            'activePage' => 'lesson',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/lesson/{lessonId}', name: 'lesson_show')]
    public function show(int $schoolId, int $lessonId): Response
    {
        $lesson = $this->lessonRepository->find($lessonId);

        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'activePage' => 'lesson',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/lesson/{lessonId}/edit', name: 'lesson_edit')]
    public function edit(Request $request, int $schoolId, int $lessonId): Response
    {
        $lesson = $this->lessonRepository->find($lessonId);
        $options = [
            'schoolId' => $schoolId,
        ];
        $form = $this->createForm(LessonType::class, $lesson, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->lessonRepository->save($lesson, true);

            return $this->redirectToRoute('lesson_index', ['schoolId' => $schoolId]);
        }

        return $this->render('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
            'activePage' => 'lesson',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/lesson/{lessonId}/delete', name: 'lesson_delete')]
    public function delete(int $schoolId, int $lessonId): Response
    {
        $this->lessonRepository->delete($lessonId);

        return $this->redirectToRoute('lesson_index', ['schoolId' => $schoolId]);
    }
}
