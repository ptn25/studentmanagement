<?php

namespace App\Controller;

use App\Entity\Lecturer;
use App\Form\LecturerType;
use App\Repository\LecturerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/lecturer')]
class LecturerController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'lecturer_index', methods: ['GET'])]
    public function index(LecturerRepository $lecturerRepository): Response
    {
        return $this->render('lecturer/index.html.twig', [
            'lecturers' => $lecturerRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/list', name: 'lecturer_list')]
    public function lecturerList () {
        $lecturers = $this->getDoctrine()->getRepository(Lecturer::class)->findAll();
        $session = new Session();
        $session->set('search', false);
        return $this->render('lecturer/list.html.twig',
            [
                'lecturers' => $lecturers
            ]);
    }

    #[Route('/detail/{id}', name: 'lecturer_detail')]
    public function lecturerDetail ($id, LecturerRepository $lecturerRepository) {
        $lecturer = $lecturerRepository->find($id);
        if ($lecturer == null) {
            $this->addFlash('Warning', 'Invalid lecturer id !');
            return $this->redirectToRoute('lecturer_index');
        }
        return $this->render('lecturer/detail.html.twig',
            [
                'lecturer' => $lecturer
            ]);
    }

    #[Route('/add', name: 'lecturer_add', methods: ['GET', 'POST'])]
    public function lecturerAdd(Request $request)
    {
        $lecturer = new Lecturer();
        $form = $this->createForm(LecturerType::class, $lecturer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($lecturer);
            $manager->flush();
            $this->addFlash('Info','Add lecturer successfully !');
            return $this->redirectToRoute('lecturer_index');
        }
        return $this->renderForm('lecturer/add.html.twig',
        [
            'lecturerForm' => $form
        ]);
    }

   

    #[Route('/edit/{id}', name: 'lecturer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lecturer $lecturer, LecturerRepository $lecturerRepository): Response
    {
        $form = $this->createForm(LecturerType::class, $lecturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lecturerRepository->add($lecturer);
            return $this->redirectToRoute('lecturer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lecturer/edit.html.twig', [
            'lecturer' => $lecturer,
            'lecturerForm' => $form,    
        ]);
    }

    #[Route('/delete/{id}', name: 'lecturer_delete')]
    public function lecturerDelete ($id, ManagerRegistry $managerRegistry) {
        $lecturer = $managerRegistry->getRepository(Lecturer::class)->find($id);
        if ($lecturer == null) {
            $this->addFlash('Warning', 'Lecturer not existed !');
        
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($lecturer);
            $manager->flush();
            $this->addFlash('Info', 'Delete lecturer successfully !');
        }
        return $this->redirectToRoute('lecturer_index');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/search', name: 'search_lecturer')]
    public function searchLecturer(LecturerRepository $lecturerRepository, Request $request) {
    $lecturers = $lecturerRepository->searchLecturer($request->get('keyword'));
    if ($lecturers == null) {
      $this->addFlash("Warning", "No lecturer found !");
    }
    $session = $request->getSession();
    $session->set('search', true);
    return $this->render('lecturer/list.html.twig', 
    [
        'lecturers' => $lecturers,
    ]);
  }
}