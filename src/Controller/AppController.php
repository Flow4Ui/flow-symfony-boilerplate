<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function home(): RedirectResponse
    {
        return $this->redirectToRoute('app_shell');
    }

    #[Route('/app/{route}', name: 'app_shell', defaults: ['route' => ''], requirements: ['route' => '.*'], methods: ['GET'])]
    public function shell(): Response
    {
        return $this->render('app_layout.html.twig');
    }
}
