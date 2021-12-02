<?php

namespace App\Controller\Authoring;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * @Route("", methods="GET")
     */
    public function index(TagRepository $repository): Response
    {
        $tags = $repository->findAll();

        return $this->render('authoring/tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();
            $this->addFlash('notice', 'Votre tag a bien été enregistrée');

            return $this->redirectToRoute('app_authoring_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('authoring/tag/create.html.twig', [
            'create_form' => $form,
        ]);
    }


    /**
     * @Route("/{id}/edit", methods={"GET", "PUT"})
     */
    public function edit(Tag $tag, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TagType::class, $tag, [
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('notice', 'Votre tag a bien été modifié');

            return $this->redirectToRoute('app_authoring_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('authoring/tag/edit.html.twig', [
            'edit_form' => $form,
        ]);
    }
}
