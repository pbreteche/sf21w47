<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    const MAX_POSTS_PER_PAGE = 10;

    /**
     * @Route("/")
     */
    public function homepage(PostRepository $repository): Response
    {
        $posts = $repository->findBy([], ['createdAt' => 'DESC'], self::MAX_POSTS_PER_PAGE);

        return $this->render('post/homepage.html.twig', [
            'title' => 'Bienvenue sur mon blog!',
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function show(int $id, PostRepository $repository): Response
    {
        $post = $repository->find($id);

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/new")
     */
    public function create(): Response
    {
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('title')
            ->add('body', TextareaType::class, [
                'attr' => [ 'cols' => 60, 'rows' => 10 ],
            ])
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->getForm()
        ;

        return $this->renderForm('post/create.html.twig', [
            'create_form' => $form,
        ]);
    }
}
