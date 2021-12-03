<?php

namespace App\Controller\Api;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    /**
     * @Route("")
     */
    public function last5(PostRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $posts = $repository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->json($posts, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
