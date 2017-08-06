<?php

namespace TimeBundle\Repository;

use TimeBundle\Entity\User;
use TimeBundle\constant\Roles;
use Doctrine\ORM\Tools\Pagination\Paginator;
use TimeBundle\Service\PaginatorService;
use Symfony\Component\Config\Definition\Exception\Exception;

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
    public function getAdminId()
    {
        $adminUsername = 'admin' ;
        $admin = $this->createQueryBuilder('user')
                ->select()
                ->where("user.username = '$adminUsername'")
                ->getQuery()
                ->execute();
        
        return $admin[0]->getId();
    }
    
    public function getMotherId($childId)
    {
        $mother = $this->createQueryBuilder('user')
                        ->select('mother.id')
                        ->where("user.id =$childId")
                        ->join('user.mother', 'mother')
                        ->getQuery()
                        ->execute();
//                dump($mother[0]['id']);
//                die();
        if(!isset( $mother[0])) 
            throw new Exception('Not child person ') ;
        
        return $mother[0]['id'];
    }

    public function createAdminUser($encodedPassword)
    {
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
    
    public function createUser($username ,$fname ,$lname ,$encodedPassword ,$role ,$age,User $mother = null)
    {
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
    
    public function getUser($userId)
    {
        $user = $this->createQueryBuilder('user')
                ->select()
                ->where("user.id = $userId")
                ->getQuery()
                ->getOneOrNullResult();
        
        return $user;   
    }
    
    public function deleteUser($userId)
    {
        $this->createQueryBuilder('user')
                ->delete()
                ->where("user.id = $userId")
                ->getQuery()
                ->execute();
    }
    
    public function getMotherChildrenId($motherId)
    {
        return $this->createQueryBuilder('user')
                ->select('user.id')
                ->where("user.mother = $motherId")
                ->getQuery()
                ->execute();      
    }
    
    public function checkMotherId($motherId)
    {
        $role = Roles::ROLE_MOTHER;
        $mother = $this->createQueryBuilder('user')
                ->select()
                ->where("user.id = $motherId AND user.role = $role")
                ->getQuery()
                ->execute();
        
        if(!isset( $mother[0])) 
            throw new Exception('Mother not found ') ;
        return TRUE;
    }

    public function getMothers($offest =1, $limit=2)
    {
        $role = Roles::ROLE_MOTHER;
        $mothers = $this->createQueryBuilder('user')
                ->select()
                ->where("user.role = $role")
                ->getQuery()
                ->setFirstResult($offest)
                ->setMaxResults($limit)
                ->execute();
        
//        dump($query);
//        die();

        return $mothers ;
        
    }
    
    public function getMothersCount()
    {
        $role = Roles::ROLE_MOTHER;
        return $this->createQueryBuilder('user')
                ->select('count(user.id)')
                ->where("user.role = $role")
                ->getQuery()
                ->getSingleScalarResult()
                ;   
    }
    
    public function getFilteredUsers($username, $role)
    {
        $query = $this->createQueryBuilder('user')->select();
        if(!is_null($username)){
            $query->where("user.username LIKE '%$username%'");
        }
        if( $role != -1 ){
            $query->andWhere("user.role = $role");
        }
        
        return $query->getQuery()->getResult();
    }
    
    public function search($name)
    {
        return $this->createQueryBuilder('user')
                ->where("user.username LIKE '%$name%'")
                ->orWhere("user.fname LIKE '%$name%'")
                ->orWhere("user.lname LIKE '%$name%'")
                ->getQuery()
                ->getResult();
    }

}
