<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
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
        return $this->render('student/detail.html.twig',
        [
            'student' => $student
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/add', name: 'student_add', methods: ['GET', 'POST'])]
    public function studentAdd(Request $request)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash('Info','Add student successfully !');
            return $this->redirectToRoute('student_index');
        }
        return $this->renderForm('student/add.html.twig',
        [
            'studentForm' => $form
        ]);
    }

    #[Route('/edit/{id}', name: 'student_edit')]
    public function studentEdit ($id, Request $request) {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Warning', 'student not existed !');
            return $this->redirectToRoute('student_index');
        } else {
            $form = $this->createForm(StudentType::class,$student);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($student);
                $manager->flush();
                $this->addFlash('Info','Edit student successfully !');
                return $this->redirectToRoute('student_index');
            }
            return $this->renderForm('student/edit.html.twig',
            [
                'studentForm' => $form
            ]);
        }
    }

    #[Route('/delete/{id}', name: 'student_delete')]
    public function studentDelete ($id, ManagerRegistry $managerRegistry) {
        $student = $managerRegistry->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Warning', 'Student not existed !');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('Info', 'Delete student successfully !');
        }
        return $this->redirectToRoute('student_index');
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
