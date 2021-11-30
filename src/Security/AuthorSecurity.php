<?php

namespace App\Security;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Component\Security\Core\Security;

class AuthorSecurity
{
    /** @var Security */
    private $security;
    /** @var AuthorRepository */
    private $repository;

    public function __construct(Security $security, AuthorRepository $repository)
    {
        $this->security = $security;
        $this->repository = $repository;
    }

    public function getAuthor(): ?Author
    {
        $user = $this->security->getUser();

        if (!$user) {
            return null;
        }

        return $this->repository->findOneBy(['authenticatedAs' => $user]);
    }
}
