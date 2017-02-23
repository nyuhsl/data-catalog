<?php
namespace AppBundle\Entity\Security;


use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;



/**
 * The user roles used by this application
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
 * @ORM\Entity()
 * @ORM\Table(name="datacatalog_user_roles")
 */
class Role implements RoleInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer", name="role_id")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    protected $role_id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    protected $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    protected $users;



    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
    /**
     * Add users
     *
     * @param \AppBundle\Entity\Security\User $users
     * @return Role
     */
    public function addUser(\AppBundle\Entity\Security\User $users)
    {
        $this->users[] = $users;
        return $this;
    }
    /**
     * Remove users
     *
     * @param \AppBundle\Entity\Security\User $users
     */
    public function removeUser(\AppBundle\Entity\Security\User $users)
    {
        $this->users->removeElement($users);
    }
    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Role
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get role_id
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->role_id;
    }


    /**
     * @see \Serializable::serialize()
     * DON'T serialize Users
     */
    public function serialize()
    {
        return \serialize(array(
            $this->role_id,
            $this->name,
            $this->role
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list(
            $this->role_id,
            $this->name,
            $this->role
        ) = \unserialize($serialized);
    }
}
