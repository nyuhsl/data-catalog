<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Hslavich\OneloginSamlBundle\Event\UserModifiedEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Ldap\Ldap;
use App\Entity\Security\User as AppUser;

class AuthenticationSuccessListener
{

    public function __construct() {
    }

    public function blockSponsoredIndividuals(AuthenticationSuccessEvent $event)
    {
        // Get the User entity.
	#$user='';
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof AppUser) {
            //var_dump("not logged in");
            //throw new AccessDeniedException('called');
            return;
        } else {
	    $roles = $user->getRoles();
	    foreach ($roles as $role) {
                if ($role == 'ROLE_SPONSORED_INDIVIDUAL') {
                    throw new AccessDeniedHttpException('Sorry, your status does not grant you access to this application. If you believe this is an error please contact the Data Catalog team ' . $user->getUsername());
		}
	    }
	} 
        
        


        // Persist the data to database.
//        $this->em->persist($user);
 //       $this->em->flush();
    }
}
