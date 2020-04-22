<?php
namespace App\Security;

use App\Exception\AccountDeletedException;
use App\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;


class UserChecker implements UserCheckerInterface
{
    protected $em;

    public function __construct(EntityManager $entityManager, Logger $logger) {
      $this->em = $entityManager;
      $this->logger = $logger;
    }

    public function checkPreAuth(UserInterface $user)
    {
        $username = $user->getUsername();
        $userRepo = $this->em->getRepository('App\Entity\Security\User');

        // loadUserByUsername will throw an exception if database user isn't found
        $userInfo = $userRepo->loadUserByUsername($username);

    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }
    }

    private function verifyDatacatUser($username)
    {
        $q = $this->createQueryBuilder('u')
                  ->where('u.username = :username')
                  ->setParameter('username', $username)
                  ->getQuery();

        try {
            $userData = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf('Unable to find database user "%s"', $username);
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return true;
    }


}
