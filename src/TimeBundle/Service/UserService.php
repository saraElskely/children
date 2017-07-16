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
}
