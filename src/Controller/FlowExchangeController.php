<?php

declare(strict_types=1);

namespace App\Controller;

use Flow\Service\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FlowExchangeController extends AbstractController
{
    public function __construct(private readonly Manager $manager)
    {
    }

    #[Route('/_flow/endpoint', name: 'flow_endpoint', methods: ['POST'])]
    public function endpoint(Request $request): Response
    {
        return $this->manager->handle($request);
    }
}
