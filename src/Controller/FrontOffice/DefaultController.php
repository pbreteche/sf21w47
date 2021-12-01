<?php

namespace App\Controller\FrontOffice;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const MAX_POSTS_PER_PAGE = 10;

    /**
     * @Route("/", methods="GET")
     */
    public function homepage(PostRepository $repository): Response
    {
        $posts = $repository->findBy([], ['createdAt' => 'DESC'], self::MAX_POSTS_PER_PAGE);

        return $this->render('front_office/default/homepage.html.twig', [
            'title' => 'Bienvenue sur mon blog!',
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods="GET")
     */
    public function show(Post $post): Response
    {
        return $this->render('front_office/default/show.html.twig', [
            'post' => $post,
        ]);
    }
}
