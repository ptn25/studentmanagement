<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'student_index', methods: ['GET'])]
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

  #[IsGranted('ROLE_USER')]
  #[Route('/list', name: 'student_list')]
  public function studentList () {
    $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
    $session = new Session();
    $session->set('search', false);
    return $this->render('student/list.html.twig',
        [
            'students' => $students
        ]);
  }

  #[Route('/detail/{id}', name: 'student_detail')]
  public function studentDetail ($id, StudentRepository $studentRepository) {
    $student = $studentRepository->find($id);
    if ($student == null) {
        $this->addFlash('Warning', 'Invalid student id !');
        return $this->redirectToRoute('student_index');
    }
    return $this->render('student/show.html.twig',
        [
            'student' => $student
        ]);
  }
    #[Route('/new', name: 'student_add', methods: ['GET', 'POST'])]
    public function new(Request $request, StudentRepository $studentRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->add($student);
            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/add.html.twig', [
            'student' => $student,
            'studentForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'student_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/edit/{id}', name: 'student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->add($student);
            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student);
        }

        return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/search', name: 'search_student')]
    public function searchStudent(StudentRepository $studentRepository, Request $request) {
    $students = $studentRepository->searchStudent($request->get('keyword'));
    if ($students == null) {
      $this->addFlash("Warning", "No student found !");
    }
    $session = $request->getSession();
    $session->set('search', true);
    return $this->render('student/list.html.twig', 
    [
        'students' => $students,
    ]);
  }
}
