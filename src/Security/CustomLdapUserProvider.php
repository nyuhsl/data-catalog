<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Security\Core\User\LdapUserProvider as BaseLdapUserProvider;
use Symfony\Component\Ldap\Security\LdapUser;
use App\Entity\Security\UserRepositoryInterface;

class CustomLdapUserProvider extends BaseLdapUserProvider implements ContainerAwareInterface
{
    private $container;
    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof LdapUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return new LdapUser($user->getEntry(), $user->getUsername(), null, $user->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'App\Entity\Security\User';
    }

    /**
     * {@inheritdoc}
     */
    protected function loadUser($username, Entry $entry)
    {

        $user = parent::loadUser($username, $entry);
        
        // Fetch the user's actual roles from our database
        $userRepository = $this->container->get('doctrine.orm.entity_manager')->getRepository('App\Entity\Security\User');        
        $databaseRoles = $userRepository->getDatabaseRoles($username);
        
        return new LdapUser($entry, $username, $user->getPassword(), $databaseRoles);
    }


    /**
     * @param ContainerInterface|NULL $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }


    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }

}
