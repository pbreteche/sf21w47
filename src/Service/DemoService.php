<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class DemoService
{
    /** @var string */
    private $companyName;
    /** @var LoggerInterface */
    private $logger;
    /** @var Security */
    private $security;
    /** @var RequestStack */
    private $stack;

    public function __construct(
        string $companyName,
        LoggerInterface $logger,
        Security $security,
        RequestStack $stack
    ) {
        $this->companyName = $companyName;
        $this->logger = $logger;
        $this->security = $security;
        $this->stack = $stack;
    }

    public function init(string $arg1, string $arg2)
    {

    }

    public function welcome(): string
    {
        $this->logger->debug(
            'dépendance de service '.
//            $this->security->getUser()->getUserIdentifier().' '.
            $this->stack->getCurrentRequest()->getUri()
        );

        return 'Bienvenue chez '.$this->companyName;
    }
}