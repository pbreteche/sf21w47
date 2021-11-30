<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Security\AuthorSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\EqualTo;

class PostController extends AbstractController
{
    const MAX_POSTS_PER_PAGE = 10;

    /**
     * @Route("/", methods="GET")
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
     * @Route("/{id}", requirements={"id": "\d+"}, methods="GET")
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        AuthorSecurity $authorSecurity
    ): Response {
        $post = (new Post())
            ->setWrittenBy($authorSecurity->getAuthor())
        ;

        $form = $this->createForm(PostType::class, $post);
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

    /**
     * @Route("/{id}/edit", methods={"GET", "PUT"})
     * @IsGranted("POST_EDIT", subject="post")
     */
    public function edit(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostType::class, $post, [
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('notice', 'Votre publication a bien été modifiée');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'edit_form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", methods={"GET", "DELETE"})
     */
    public function delete(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createFormBuilder(null, [
            'method' => 'DELETE',
        ])
            ->add('challenge', TextType::class, [
                'label' => false,
                'help' => 'Veuillez recopier le titre de la publi: <kbd>'.htmlspecialchars($post->getTitle()).'</kbd>',
                'help_html' => true,
                'constraints' => new EqualTo(['value' => $post->getTitle()]),
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($post);
            $manager->flush();
            $this->addFlash('notice', 'Votre publication a bien été supprimée');

            return $this->redirectToRoute('app_post_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/delete.html.twig', [
            'delete_form' => $form,
            'post' => $post,
        ]);
    }
}
