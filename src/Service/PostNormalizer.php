<?php

namespace App\Service;

use App\Entity\Post;

class PostNormalizer
{
    /**
     * @param Post[] $posts
     */
    public function normalize(array $posts)
    {
        return array_map(function (Post $post) {
            return [
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
                'created_at' => $post->getCreatedAt()->format('c')
            ];
        }, $posts);
    }
}
