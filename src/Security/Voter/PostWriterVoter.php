<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Security\AuthorSecurity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostWriterVoter extends Voter
{
    /** @var AuthorSecurity */
    private $authorSecurity;

    public function __construct(AuthorSecurity $authorSecurity)
    {
        $this->authorSecurity = $authorSecurity;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['POST_EDIT', 'POST_DELETE'])
            && $subject instanceof Post;
    }

    /**
     * @param Post $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'POST_EDIT':
            case 'POST_DELETE':
                return $subject->getWrittenBy() === $this->authorSecurity->getAuthor();
        }

        return false;
    }
}
