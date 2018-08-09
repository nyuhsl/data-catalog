<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Security\Role;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
class LoadFixtures implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $new_roles=array(
           "Administrator" => "ROLE_ADMIN",
           "User" => "ROLE_USER",
           "API User" => "ROLE_API_SUBMITTER",
        );

        foreach($new_roles as $name => $symfony_role) {

           $role = new Role();
           $role->setName($name);
           $role->setRole($symfony_role);
           $manager->persist($role);

        }

        $manager->flush();

   }

}	
        
