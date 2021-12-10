<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\PostCriteriaBuilder;
use App\Service\PostNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", defaults={"_format": "json"})
 */
class PostRestController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(
        Request $request,
        PostCriteriaBuilder $builder,
        PostRepository $repository,
        PostNormalizer $normalizer
    ): JsonResponse {
        $criteria = $builder->build($request->query);
        $posts = $repository->findFromCriteria($criteria);
        $normalizedPosts = $normalizer->normalize($posts);

        return $this->json($normalizedPosts);
    }
}
