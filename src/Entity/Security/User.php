<?php
namespace App\Entity\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * A user account
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
 *
 * @ORM\Entity(repositoryClass="App\Entity\Security\UserRepository")
 * @ORM\Table(name="datacatalog_users")
 */
class User implements UserInterface, EquatableInterface, \Serializable
{
  /**
   * @ORM\Column(type="integer", name="user_id")
   * @ORM\Id
   * @ORM\GeneratedValue()
   */
  protected $user_id;

  /**
   * @ORM\Column(type="string", length=25, unique=true)
   */
  protected $username;


  /**
   * @ORM\Column(type="string", length=25, unique=true, nullable=true)
   */
  protected $slug;


  /**
   * @ORM\Column(type="string",length=64, nullable=true)
   */
  protected $password;


  /**
   * @ORM\Column(type="string", length=50)
   */
  protected $firstName;


  /**
   * @ORM\Column(type="string", length=50)
   */
  protected $lastName;


  /**
   * @ORM\Column(type="string", length=255, unique=true, nullable=true)
   */
  protected $apiKey;


  /**
   * @ORM\Column(type="json")
   */
  protected $roles = [];


  public function getRoles() {
    $roles = $this->roles;
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles) {
    $this->roles = $roles;

    return $this;
  }

    public function __construct()
    {

    }
    
  /**
   * Get name for display
   *
   * @return string
   */
    public function getDisplayName() {
      return $this->username;
    }



    /**
     * Get password (not used)
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get salt
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
      return $this->firstName;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Erase credentials
     */
    public function eraseCredentials()
    {
    }

    /** 
     * Required by interface
     *
     * @param UserInterface
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }




    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set API key
     *
     * @param string $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get API key
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
      /*
       * Don't serialize Roles
       */
        return \serialize(array(
            $this->user_id,
            $this->username,
            $this->slug,
            $this->password,
            $this->roles,
            $this->firstName,
            $this->lastName
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->user_id,
            $this->username,
            $this->slug,
            $this->password,
            $this->firstName,
            $this->lastName,
            $this->roles
        ) = \unserialize($serialized);
    }

}
