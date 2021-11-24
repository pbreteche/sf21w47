<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function index(): Response
    {
        return new Response('Ma première réponse');
    }

    public function exo1(string $name): Response
    {
        return new Response('<p>Bonjour '.$name.'!</p>');
    }
}
