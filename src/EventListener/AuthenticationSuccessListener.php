<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Entity\Security\User as AppUser;

class AuthenticationSuccessListener
{

    protected $usernameAttribute;
    protected $userRoleAttribute;
    protected $rolesToBlock;

    public function __construct(EntityManagerInterface $em, 
                                $usernameAttribute = null,
                                $userRoleAttribute = null,
                                $rolesToBlock      = null)
    {
       $this->em = $em; 
       $this->usernameAttribute = $usernameAttribute; 
       $this->userRoleAttribute = $userRoleAttribute; 
       $this->rolesToBlock      = $rolesToBlock; 
    }

    public function blockSponsoredIndividuals(AuthenticationSuccessEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof AppUser) {
            return;
        } else {
            $attributes = $event->getAuthenticationToken()->getAttributes();
            $userID = $attributes[$this->usernameAttribute][0];
            $user->setUsername($userID);
            if (isset($this->userRoleAttribute) && isset($this->rolesToBlock)) {
                $institutionalRoles = $attributes[$this->userRoleAttribute];
                if (count(array_intersect($institutionalRoles, $this->rolesToBlock)) > 0) {
                    $user->addRole('ROLE_DENIED_ACCESS');
                }
            }
            // Persist the user to database.
            $this->em->persist($user);
            $this->em->flush();
            
            $roles = $user->getRoles();
            foreach ($roles as $role) {
                if ($role == 'ROLE_DENIED_ACCESS') {
                    throw new AccessDeniedHttpException();
                }
	        }
	    } 
    }
}
