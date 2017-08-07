<?php


namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\User;
use TimeBundle\constant\Roles;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
 * Description of UserService
 *
 * @author saraelsayed
 */
class UserService {
    private $entityManager;
    
    const EDIT = 'edit';
    const DELETE = 'delete';
    const SHOW = 'show';

    
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
        return $this->entityManager->getRepository('TimeBundle:User')
                ->getFilteredUsers($username, $role);
    }
    public function denyAccessUnlessShowChildrenGranted($user, $motherId)
    {
        if( $user->getRole() === Roles::ROLE_ADMIN && 
                $this->entityManager->getRepository('TimeBundle:User')->checkMotherId($motherId)) {
            return TRUE;
        }elseif ( $user->getRole() === Roles::ROLE_MOTHER && $motherId == $user->getId()) {
            return TRUE;
        } 
        throw new AccessDeniedException();
    }
    private function isValid($action, $user)
    {
        return (in_array($action, array(self::DELETE, self::EDIT, self::SHOW)) ||  $user instanceof User ) ;
    }
    public function denyAccessUnlessGranted($action, $currUser, $user)
    {
        if($this->isValid($action, $user)){    
            switch ($currUser->getRole()) {
                case Roles::ROLE_ADMIN :
                    switch ($action) {
                        case self::SHOW :
                            return TRUE;
                        case self::EDIT :
                            if( $currUser->getId() === $user->getId()) {
                                return TRUE;
                            }
                            break;
                        case self::DELETE :
                            $this->entityManager->getRepository('TimeBundle:User')->checkMotherId($user->getId());
                    }
                    break;
                case Roles::ROLE_MOTHER : 
                    switch ($action) {
                        case self::SHOW :
                        case self::EDIT :
                            if( $currUser->getId() === $user->getId() ||
                                    $currUser->getId() === $this->entityManager->getRepository('TimeBundle:User')->getMotherId($user->getId())) {
                                return TRUE;
                            }
                            break;
                        case self::DELETE :
                            if( $currUser->getId() === $this->entityManager->getRepository('TimeBundle:User')->getMotherId($user->getId())) {
                                return TRUE;
                            }
                    }
                    break;
                case Roles::ROLE_CHILD :
                    if( $action === self::SHOW && $currUser->getId() === $user->getId()) {
                        return TRUE;
                    }
            }
        }
        throw new AccessDeniedException();
    }



}
