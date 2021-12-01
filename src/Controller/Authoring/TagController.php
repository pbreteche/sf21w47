<?php

namespace App\Controller\Authoring;

use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/authoring/tag")
 * @IsGranted("ROLE_AUTHOR")
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

}
