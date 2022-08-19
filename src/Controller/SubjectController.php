<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/Subject')]
class SubjectController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'Subject_index', methods: ['GET'])]
    public function index(SubjectRepository $SubjectRepository): Response
    {
        return $this->render('Subject/index.html.twig', [
            'Subjects' => $SubjectRepository->findAll(),
        ]);
    }

  #[IsGranted('ROLE_USER')]
  #[Route('/list', name: 'Subject_list')]
  public function SubjectList () {
    $Subjects = $this->getDoctrine()->getRepository(Subject::class)->findAll();
    $session = new Session();
    $session->set('search', false);
    return $this->render('Subject/list.html.twig',
        [
            'Subjects' => $Subjects
        ]);
  }

  #[Route('/detail/{id}', name: 'Subject_detail')]
  public function SubjectDetail ($id, SubjectRepository $SubjectRepository) {
    $Subject = $SubjectRepository->find($id);
    if ($Subject == null) {
        $this->addFlash('Warning', 'Invalid Subject id !');
        return $this->redirectToRoute('Subject_index');
    }
    return $this->render('Subject/show.html.twig',
        [
            'Subject' => $Subject
        ]);
  }
    #[Route('/new', name: 'Subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SubjectRepository $SubjectRepository): Response
    {
        $Subject = new Subject();
        $form = $this->createForm(SubjectType::class, $Subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $SubjectRepository->add($Subject);
            return $this->redirectToRoute('Subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Subject/new.html.twig', [
            'Subject' => $Subject,
            'SubjectForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'Subject_show', methods: ['GET'])]
    public function show(Subject $Subject): Response
    {
        return $this->render('Subject/show.html.twig', [
            'Subject' => $Subject,
        ]);
    }

    #[Route('/edit/{id}', name: 'Subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subject $Subject, SubjectRepository $SubjectRepository): Response
    {
        $form = $this->createForm(SubjectType::class, $Subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $SubjectRepository->add($Subject);
            return $this->redirectToRoute('Subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Subject/edit.html.twig', [
            'Subject' => $Subject,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'Subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $Subject, SubjectRepository $SubjectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Subject->getId(), $request->request->get('_token'))) {
            $SubjectRepository->remove($Subject);
        }

        return $this->redirectToRoute('Subject_index', [], Response::HTTP_SEE_OTHER);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/search', name: 'search_Subject')]
    public function searchSubject(SubjectRepository $SubjectRepository, Request $request) {
    $Subjects = $SubjectRepository->searchSubject($request->get('keyword'));
    if ($Subjects == null) {
      $this->addFlash("Warning", "No Subject found !");
    }
    $session = $request->getSession();
    $session->set('search', true);
    return $this->render('Subject/list.html.twig', 
    [
        'Subjects' => $Subjects,
    ]);














  }
}
