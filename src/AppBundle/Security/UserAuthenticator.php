<?php
namespace AppBundle\Security;

use adLDAP\adLDAP;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Authorize against our user database, and authenticate against LDAP
 */
class UserAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            //search our database to see if this person has been granted access
            $userData = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Please use the \'Contact Us\' link above to request access to the Data Catalog');
        }
        $username = $userData->getUsername();

        // set up LDAP connection
        $ldap = new adLDAP();

        // authenticate against LDAP
        $passwordValid = $ldap->authenticate($username,$token->getCredentials());

        // if they authenticated successfully, return a user token
        if ($passwordValid) {
          return new UsernamePasswordToken(
            $username,
            $userData->getPassword(),
            $providerKey,
            $userData->getRoles()
          );
        } else {
          throw new AuthenticationException('Invalid username or password');
        }
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
