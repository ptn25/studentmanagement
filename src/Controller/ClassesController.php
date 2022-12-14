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

    #[Route('/add', name: 'classes_add', methods: ['GET', 'POST'])]
    public function classAdd(Request $request)
    {
        $class = new Classes();
        $form = $this->createForm(ClassesType::class, $class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($class);
            $manager->flush();
            $this->addFlash('Info','Add class successfully !');
            return $this->redirectToRoute('classes_index');
        }
        return $this->renderForm('classes/add.html.twig',
        [
            'classForm' => $form
        ]);
    }

    #[Route('/detail/{id}', name: 'classes_detail')]
    public function classDetail($id, ClassesRepository $classesRepository)
    {
        $class = $classesRepository->find($id);
        if ($class == null) {
            $this->addFlash('Warning', 'Invalid class id !');
            return $this->redirectToRoute('classes_index');
        }
        return $this->render('classes/detail.html.twig',
            [
                'class' => $class
            ]
        );
    }

    #[Route('/{id}/edit', name: 'classes_edit')]
    public function classEdit ($id, Request $request) {
        $class = $this->getDoctrine()->getRepository(Classes::class)->find($id);
        if ($class == null) {
            $this->addFlash('Warning', 'Class not existed !');
            return $this->redirectToRoute('classes_index');
        } else {
            $form = $this->createForm(ClassesType::class,$class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($class);
                $manager->flush();
                $this->addFlash('Info','Edit class successfully !');
                return $this->redirectToRoute('classes_index');
            }
            return $this->renderForm('classes/edit.html.twig',
            [
                'classesForm' => $form
            ]);
        }
    }

    #[Route('delete/{id}', name: 'classes_delete')]
    public function classDelete ($id, ManagerRegistry $managerRegistry) {
        $class = $managerRegistry->getRepository(Classes::class)->find($id);
        if ($class == null) {
            $this->addFlash('Warning', 'Class not existed !');
        }
        //check xem con student trong class khong truoc khi xoa
        else if (count($class->getStudents()) > 0){
            $this->addFlash('Warning', 'Can not delete this class !');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($class);
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
