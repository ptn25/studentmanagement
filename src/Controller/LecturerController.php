<?php

namespace App\Controller;

use App\Entity\Lecturer;
use App\Form\LecturerType;
use App\Repository\LecturerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/lecturer")
 */
class LecturerController extends AbstractController
{
    /**
     * @Route("/index", name="lecturer_index")
     */
    public function lecturerIndex()
    {
        $lecturers = $this->getDoctrine()->getRepository(Lecturer::class)->findAll();
        return $this->render('lecturer/index.html.twig',
        [
            'lecturers' => $lecturers
        ]);
    }

    /**
     * @Route("/list", name="lecturer_list")
     */
    public function lecturerList()
    {
        $lecturers = $this->getDoctrine()->getRepository(Lecturer::class)->findAll();
        return $this->render('lecturer/list.html.twig',
        [
            'lecturers' => $lecturers
        ]);
    }

    /**
     * @Route("/detail/{id}", name="lecturer_detail")
     */
    public function LecturerDetail($id, LecturerRepository $lecturerRepository)
    {
        $lecturer = $lecturerRepository->find($id);
        if ($lecturer == null) {
            $this->addFlash('Warning', 'Invalid lecturer id !');
            return $this->redirectToRoute('lecturer_index');
        }
        return $this->render('lecturer/detail.html.twig',
            [
                'lecturer' => $lecturer
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="lecturer_delete")
     */
    public function lecturerDelete($id, ManagerRegistry $managerRegistry)
    {
        $lecturer = $managerRegistry->getRepository(Lecturer::class)->find($id);
        if ($lecturer == null) {
            $this->addFlash('Warning', 'Lecturer not existed !');
            return $this->redirectToRoute('lecturer_index');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($lecturer);
            $manager->flush();
            $this->addFlash('Info', 'Delete lecturer successfully !');
        }
        return $this->redirectToRoute('lecturer_index');
    }

    /**
     * @Route("/add", name="lecturer_add")
     */
    public function lecturerAdd (Request $request) {
        $lecturer = new Lecturer;
        $form = $this->createForm(LecturerType::class,$lecturer);
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

    /**
     * @Route("/edit/{id}", name="lecturer_edit")
     */
    public function lecturerEdit ($id, Request $request) {
        $lecturer = $this->getDoctrine()->getRepository(Lecturer::class)->find($id);
        if ($lecturer == null) {
            $this->addFlash('Warning', 'Lecturer not existed !');
            return $this->redirectToRoute('lecturer_index');
        } else {
            $form = $this->createForm(LecturerType::class,$lecturer);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($lecturer);
                $manager->flush();
                $this->addFlash('Info','Edit lecturer successfully !');
                return $this->redirectToRoute('lecturer_index');
            }
            return $this->renderForm('lecturer/edit.html.twig',
            [
                'lecturerForm' => $form
            ]);
        }
    }
}