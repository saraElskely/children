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

    public function getMotherId($childId)
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getMotherId($childId);
    }

    public function getMothers($offest =1, $limit =2)
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getMothers($offest,$limit);
        
    }
    public function getMothersCount()
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getMothersCount();
    }
    public function getFilteredUsers($username, $role)
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getFilteredUsers($username, $role);
    }


//    private function isValid($action, $user)
//    {
//        return (!in_array($action, array('new', 'edit', 'delete', 'show', 'index')) || ! $user instanceof User ) ;
//    }
//
//    public function denyAccessUnlessGranted($action, $user, $task = null)
//    {
//        if($this->isValid($action, $user)){
//            switch ($action){
//                case 'index':
//                case 'new': 
//                    if($user->getRole() === Roles::ROLE_ADMIN || $user->getRole() === Roles::ROLE_MOTHER )
//                        return TRUE;
//                case 'edit':
//                case 'delete':
//                case 'show':
//                    if(! $task && ! $task instanceof Task) 
//                        throw new Exception('task not found');
//                    elseif ($user->getId() === $task->getCreator()->getId()) 
//                         return TRUE ;
//            }
//        }
//        throw new AccessDeniedException();
//    }

}
