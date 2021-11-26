<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, [
                'help' => 'Pas plus long que 60 caractères',
            ])
            ->add('body', TextareaType::class, [
                'attr' => [ 'cols' => 60, 'rows' => 10 ],
                'help' => 'Écrivez un contenu suffisamment long (10).',
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($post);
            $manager->flush();
            $this->addFlash('notice', 'Votre publication a bien été enregistrée');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/create.html.twig', [
            'create_form' => $form,
        ]);
    }
}
