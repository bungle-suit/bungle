<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\StateMachine\EventListener;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FakeAuthorizationChecker implements AuthorizationCheckerInterface
{
    private string $wantedRole;
    public function __construct(string $wantedRole)
    {
        $this->wantedRole = $wantedRole;
    }
  
    public function isGranted($attribute, $subject = null): bool
    {
        if (!is_string($attribute)) {
            return false;
        }

        return $attribute == $this->wantedRole;
    }
}