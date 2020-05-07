<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use App\Entity\Security\User;

class ApiUserProvider implements UserProviderInterface {

    protected $user;
    public function __construct (UserInterface $user) {
        $this->user = $user;
        var_dump("using API provider");
    }

    /**
     * @throws UsernameNotFoundException if the user is not found
     * @param string $username The username
     *
     * @return UserInterface
     */
    function loadUserByUsername($apiKey) {
        var_dump("using API provider");
        $user = User::find(array('apiKey'=>$apiKey));
        if(empty($user)){
            throw new UsernameNotFoundException('Could not find user. Sorry!');
        }
        $this->user = $user;
        return $user;
    }

    /**
     * @throws UnsupportedUserException if the account is not supported
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    function refreshUser(UserInterface $user) {
        return $user;
    }

    /**
     * @param string $class
     *
     * @return Boolean
     */
    function supportsClass($class) {
        return $class === 'App\Entity\Security\User';
    }
}
