<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Form\ClassesType;
use App\Repository\ClassesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/classes')]
class ClassesController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'classes_index', methods: ['GET'])]
    public function index(classesRepository $classesRepository): Response
    {
        return $this->render('classes/index.html.twig', [
            'classes' => $classesRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/list', name: 'classes_list')]
    public function classesList () {
        $classes = $this->getDoctrine()->getRepository(classes::class)->findAll();
        $session = new Session();
        $session->set('search', false);
        return $this->render('classes/list.html.twig',
            [
                'classes' => $classes
            ]);
    }

    #[Route('/new', name: 'classes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClassesRepository $classesRepository): Response
    {
        $class = new Classes();
        $form = $this->createForm(ClassesType::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classesRepository->add($class);
            return $this->redirectToRoute('classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classes/new.html.twig', [
            'class' => $class,
            'classesForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'classes_detail', methods: ['GET'])]
    public function show(Classes $class): Response
    {
        return $this->render('classes/show.html.twig', [
            'class' => $class,
        ]);
    }

    #[Route('/{id}/edit', name: 'classes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classes $class, ClassesRepository $classesRepository): Response
    {
        $form = $this->createForm(ClassesType::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classesRepository->add($class);
            return $this->redirectToRoute('classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classes/edit.html.twig', [
            'class' => $class,
            'classesForm' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'classes_delete')]
    public function classDelete ($id, ManagerRegistry $managerRegistry) {
        $classes = $managerRegistry->getRepository(Classes::class)->find($id);
        if ($classes == null) {
            $this->addFlash('Warning', 'classes not existed !');
        
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($classes);
            $manager->flush();
            $this->addFlash('Info', 'Delete class successfully !');
        }
        return $this->redirectToRoute('classes_index');
      }
    #[IsGranted('ROLE_USER')]
    #[Route('/search', name: 'search_classes')]
    public function searchClasses(ClassesRepository $ClassesRepository, Request $request) {
    $classes = $ClassesRepository->searchClasses($request->get('keyword'));
    if ($classes == null) {
      $this->addFlash("Warning", "No Classes found !");
    }
    $session = $request->getSession();
    $session->set('search', true);
    return $this->render('Classes/list.html.twig', 
    [
        'classes' => $classes,
    ]);
    }
}
