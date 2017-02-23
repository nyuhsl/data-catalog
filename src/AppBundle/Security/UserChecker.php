<?php
namespace AppBundle\Security;

use AppBundle\Exception\AccountDeletedException;
use AppBundle\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;


/*
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
 */
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
        $userRepo = $this->em->getRepository('AppBundle\Entity\Security\User');

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
