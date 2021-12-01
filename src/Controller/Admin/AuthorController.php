<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accounts")
 * @IsGranted("ROLE_ADMIN")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/subscribe")
     */
    public function subscribe(Request $request, EntityManagerInterface $manager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($author);
            $manager->flush();

            $this->addFlash('notice', 'Le compte '.$author->getName().' a bien été créé');
            return $this->redirectToRoute('app_frontoffice_default_homepage');
        }

        return $this->renderForm('author/subscribe.html.twig', [
            'form' => $form,
        ]);
    }
}
