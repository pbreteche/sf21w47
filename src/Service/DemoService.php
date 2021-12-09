<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class DemoService
{
    /** @var string */
    private $companyName;
    /** @var LoggerInterface */
    private $logger;
    /** @var Security */
    private $security;

    public function __construct(string $companyName, LoggerInterface $logger, Security $security)
    {
        $this->companyName = $companyName;
        $this->logger = $logger;
        $this->security = $security;
    }

    public function welcome(): string
    {
        $this->logger->debug('dépendance de service '.$this->security->getUser()->getUserIdentifier());

        return 'Bienvenue chez '.$this->companyName;
    }
}