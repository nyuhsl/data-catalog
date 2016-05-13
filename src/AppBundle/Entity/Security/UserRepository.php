<?php
namespace AppBundle\Entity\Security;

use AppBundle\Entity\Security\User;
use AppBundle\Entity\Security\Role;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


/**
 * A Doctrine entity repository to provide custom queries on our users
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * Load user from database
     *
     * @param string $username The username
     *
     * @return mixed The user's data
     *
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
      // make a call to database to see if user exists, roles
      $q = $this->createQueryBuilder('u')
                ->where('u.username = :username')
                ->setParameter('username',$username)
                ->getQuery();
      try {
        $userData = $q->getSingleResult();
      } catch (NoResultException $e) {
        $message = sprintf(
          'Unable to find user "%s"', $username
        );
        throw new UsernameNotFoundException($message, 0, $e);
      }

      return $userData;

    }


    /**
     * Reload user data
     *
     * @param UserInterface
     *
     * @return mixed The user's data
     *
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }


    /**
     * Check if a given class is identical to User class
     *
     * @param mixed $class The class we're checking
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'AppBundle\Entity\Security\User';
    }
}
