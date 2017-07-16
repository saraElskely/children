<?php

namespace TimeBundle\Repository;

use TimeBundle\Entity\User;
use TimeBundle\constant\Roles;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getChildrenQueryBuilder()
    {
        return $this->createQueryBuilder('user')
                  ->select()
                  ->where('user.role = 3');

    }
    public function getAdminId(){
        $adminUsername = 'admin' ;
        $admin = $this->createQueryBuilder('user')
                ->select()
                ->where("user.username = '$adminUsername'")
                ->getQuery()
                ->execute();
        
        return $admin[0]->getId();
    }
    
    public function createAdminUser($encodedPassword){
        
        $admin = new User();
        
        $admin->setUsername('admin')
                ->setRole(Roles::ROLE_ADMIN)
                ->setAge(30)
                ->setFname('admin')
                ->setLname('admin')
                ->setPassword($encodedPassword);
        
        
        $entityManager = $this->getEntityManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        return $admin;
    }
    
    public function createUser($username ,$fname ,$lname ,$encodedPassword ,$role ,$age,User $mother = null){
        $user = new User();
        $user->setAge($age)
                ->setFname($fname)
                ->setLname($lname)
                ->setPassword($encodedPassword)
                ->setRole($role)
                ->setUsername($username)
                ->setMother($mother);
        
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
