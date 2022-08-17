<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LecturerController extends AbstractController
{
    #[Route('/lecturer', name: 'app_lecturer')]
    public function index(): Response
    {
        return $this->render('lecturer/index.html.twig', [
            'controller_name' => 'LecturerController',
        ]);
    }
}
