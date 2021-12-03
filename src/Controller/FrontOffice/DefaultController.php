<?php

namespace App\Controller\FrontOffice;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const MAX_POSTS_PER_PAGE = 10;

    /**
     * @Route("/", methods="GET")
     */
    public function homepage(
        Request $request,
        PostRepository $repository,
        UserRepository $userRepository
    ): Response {
        $selectedAuthorName = $request->query->get('author');
        $criteria = [];
        if ($selectedAuthorName) {
            if ($selectedAuthor = $userRepository->findOneBy(['name' => $selectedAuthorName])) {
                $criteria = ['writtenBy' => $selectedAuthor];
            }
        }

        $posts = $repository->findBy($criteria, ['createdAt' => 'DESC'], self::MAX_POSTS_PER_PAGE);

        return $this->render('front_office/default/homepage.html.twig', [
            'title' => 'Bienvenue sur mon blog!',
            'posts' => $posts,
            'authors' => $userRepository->findBy([], ['name' => 'ASC']),
            'selected_author' => $selectedAuthor ?? null,
        ]);
    }

    /**
     * @Route("/{slug}", methods="GET")
     */
    public function show(Post $post): Response
    {
        return $this->render('front_office/default/show.html.twig', [
            'post' => $post,
        ]);
    }
}
