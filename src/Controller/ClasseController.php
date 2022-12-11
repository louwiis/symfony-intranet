<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use App\Repository\SchoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClasseController extends AbstractController
{
    private ClasseRepository $classeRepository;
    private SchoolRepository $schoolRepository;

    public function __construct(ClasseRepository $classeRepository, SchoolRepository $schoolRepository)
    {
        $this->classeRepository = $classeRepository;
        $this->schoolRepository = $schoolRepository;
    }

    #[Route('/{schoolId}/classe', name: 'classe_index')]
    public function index(int $schoolId): Response
    {
        $classes = $this->classeRepository->findAllBySchoolId($schoolId);

        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
            'activePage' => 'classe',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/classe/{classeId}', name: 'classe_show')]
    public function show(int $schoolId, int $classeId): Response
    {
        $classe = $this->classeRepository->find($classeId);

        if ($classe->getSchool()->getId() !== $schoolId) {
            return $this->redirectToRoute('classe_index', ['schoolId' => $schoolId]);
        }

        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
            'activePage' => 'classe',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/classe/{classeId}/edit', name: 'classe_edit')]
    public function edit(Request $request, int $schoolId, int $classeId): Response
    {
        $classe = $this->classeRepository->find($classeId);
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->classeRepository->save($classe, true);

            return $this->redirectToRoute('classe_index', ['schoolId' => $schoolId]);
        }

        return $this->render('classe/edit.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'classe',
            'schoolId' => $schoolId,
        ]);
    }
    
    #[Route('/{schoolId}/classe-add', name: 'classe_new')]
    public function new(Request $request, int $schoolId): Response
    {
        $classe = new Classe();
        $classe->setSchool($this->schoolRepository->find($schoolId));
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->classeRepository->save($classe, true);

            return $this->redirectToRoute('classe_index', ['schoolId' => $schoolId]);
        }

        return $this->render('classe/new.html.twig', [
            'form' => $form->createView(),
            'activePage' => 'classe',
            'schoolId' => $schoolId,
        ]);
    }

    #[Route('/{schoolId}/classe/{classeId}/delete', name: 'classe_delete')]
    public function delete(int $schoolId, int $classeId): Response
    {
        $this->classeRepository->delete($classeId);

        return $this->redirectToRoute('classe_index', ['schoolId' => $schoolId]);
    }
}
    