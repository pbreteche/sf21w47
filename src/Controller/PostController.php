<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post")
     */
    public function homepage(): Response
    {
        return $this->render('post/index.html.twig', [
            'title' => 'Bienvenue sur mon blog!',
        ]);
    }
}
