<?php


namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\User;
use TimeBundle\constant\Roles;
/**
 * Description of UserService
 *
 * @author saraelsayed
 */
class UserService {
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager ;
    }
    
    public function createUser($username ,$fname ,$lname ,$encodedPassword ,$role ,$age ,User $mother = null){
        $this->entityManager->getRepository('TimeBundle:User')->createUser($username ,$fname ,$lname ,$encodedPassword ,$role ,$age ,$mother);
    }
    public function getUser($userId)
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getUser($userId);
    }
    
    public function deleteUser($userId)
    {
        return $this->entityManager->getRepository('TimeBundle:User')->deleteUser($userId);
    }


    public function getMothers($offest =1, $limit =2)
    {
        $count = $this->entityManager->getRepository('TimeBundle:User')->getMothersCount();
        return $this->entityManager->getRepository('TimeBundle:User')->getMothers($offest,$limit);
        
    }
    public function getMothersCount()
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getMothersCount();
    }

}
