<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolType;
use App\Form\AddressType;
use App\Repository\SchoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{
    private SchoolRepository $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    #[Route('/', name: 'school_index')]
    public function index(): Response
    {
        $schools = $this->schoolRepository->findAll();

        return $this->render('school/index.html.twig', [
            'schools' => $schools,
            'activePage' => 'home',
        ]);
    }

    #[Route('/{schoolId}/show', name: 'school_show')]
    public function show(int $schoolId): Response
    {
        $school = $this->schoolRepository->find($schoolId);

        return $this->render('school/show.html.twig', [
            'school' => $school,
            'activePage' => 'showSchool',
        ]);
    }

    #[Route('/{schoolId}/edit', name: 'school_edit')]
    public function edit(Request $request, int $schoolId): Response
    {
        $school = $this->schoolRepository->find($schoolId);
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->schoolRepository->save($school, true);

            return $this->redirectToRoute('school_index');
        }

        return $this->render('school/edit.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'editSchool',
        ]);
    }
    
    #[Route('/school-add', name: 'school_new')]
    public function new(Request $request): Response
    {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->schoolRepository->save($school, true);

            return $this->redirectToRoute('school_index');
        }

        return $this->render('school/new.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'newSchool',
        ]);
    }

    #[Route('/{schoolId}/delete', name: 'school_delete')]
    public function delete(int $schoolId): Response
    {
        $this->schoolRepository->delete($schoolId);

        return $this->redirectToRoute('school_index');
    }
}
    