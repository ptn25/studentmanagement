<?php

namespace App\Controller;

use App\Entity\Lecturer;
use App\Form\Lecturer1Type;
use App\Repository\StudentRepository;
use App\Repository\LecturerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
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

    #[Route('/detail/{id}', name: 'lecturer_detail', methods: ['GET'])]
     public function lecturerDetail ($id, LecturerRepository $lecturerRepository) {
            $lecturer = $lecturerRepository->find($id);
            if ($lecturer == null) {
                $this->addFlash('Warning', 'Invalid lecturer id !');
                return $this->redirectToRoute('lecturer_index');
            }
            return $this->render('lecturer/show.html.twig',
                [
                    'lecturer' => $lecturer
                ]);
    }
    #[Route('/new', name: 'app_lecturer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LecturerRepository $lecturerRepository): Response
    {
        $lecturer = new Lecturer();
        $form = $this->createForm(Lecturer1Type::class, $lecturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lecturerRepository->add($lecturer);
            return $this->redirectToRoute('lecturer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lecturer/new.html.twig', [
            'lecturer' => $lecturer,
            'form' => $form,
        ]);
    }

   

    #[Route('/edit/{id}', name: 'lecturer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lecturer $lecturer, LecturerRepository $lecturerRepository): Response
    {
        $form = $this->createForm(Lecturer1Type::class, $lecturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lecturerRepository->add($lecturer);
            return $this->redirectToRoute('lecturer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lecturer/edit.html.twig', [
            'lecturer' => $lecturer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lecturer_delete', methods: ['POST'])]
    public function delete(Request $request, Lecturer $lecturer, LecturerRepository $lecturerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lecturer->getId(), $request->request->get('_token'))) {
            $lecturerRepository->remove($lecturer);
        }

        return $this->redirectToRoute('lecturer_index', [], Response::HTTP_SEE_OTHER);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/search', name: 'search_lecturer')]
    public function searchStudent(StudentRepository $studentRepository, Request $request) {
    $students = $studentRepository->searchStudent($request->get('keyword'));
    if ($students == null) {
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