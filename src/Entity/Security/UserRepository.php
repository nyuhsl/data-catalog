<?php
namespace App\Entity\Security;

use App\Entity\Security\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


/**
 * A Doctrine entity repository to provide custom queries on our users
 *
 *   This file is part of the Data Catalog project.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
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
          'Unable to find database user "%s"', $username
        );
        throw new UsernameNotFoundException($message, 0, $e);
      }

      return $userData;

    }


   public function getDatabaseRoles($username) {
    
      $q = $this->createQueryBuilder('u')
                ->where('u.username = :username')
                ->setParameter('username',$username)
                ->getQuery();
      try {
        $userData = $q->getSingleResult();
      } catch (NoResultException $e) {
        $message = sprintf(
          'Unable to find database user "%s"', $username
        );
        throw new UsernameNotFoundException($message, 0, $e);
      }
     
      $databaseRoles = $userData->getRoles();

      return $databaseRoles;
    }


    public function loadUserByApiKey($apiKey)
    {
      // make a call to database to see if user exists, roles
      $q = $this->createQueryBuilder('u')
                ->where('u.apiKey = :apiKey')
                ->setParameter('apiKey',$apiKey)
                ->getQuery();
      try {
        $userData = $q->getSingleResult();
      } catch (NoResultException $e) {
        $message = sprintf(
          'Unable to find user with API key: "%s"', $username
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
        return $class === 'App\Entity\Security\User';
    }
}
