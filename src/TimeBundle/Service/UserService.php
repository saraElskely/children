<?php


namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\User;
use TimeBundle\constant\Roles;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use TimeBundle\Utility\Paginator;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;
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

    public function getMothers()
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getMothers();       
    }
    public function getChildren($motherId)
    {
        return $this->entityManager->getRepository('TimeBundle:User')->getChildren($motherId);
    }
    public function getUsers($page = 1, $limit =2)
    {
        $query = $this->entityManager->getRepository('TimeBundle:User')->getUsersQuery();
        $resultCount = $this->entityManager->getRepository('TimeBundle:User')->getQueryCount($query);
        $paginator = new Paginator($resultCount);
        $maxPages = $paginator->getMaxPage();
        
        if($page > $maxPages || $page < 1) {
            throw new TimeBundleException(Exceptions::CODE_PAGE_NUM_NOT_FOUND);
        }
        $offest = $paginator->getOffest($page);
        
        $users = $this->entityManager->getRepository('TimeBundle:User')->getUsers($query, $offest, $limit);
        return [
            'users' => $users,
            'maxPages' => $maxPages,
            'currentPage' => $page
        ];
    }
    public function getFilteredUsers($username = null, $role = null, $page =1, $limit = 3)
    {
        $query = $this->entityManager->getRepository('TimeBundle:User')
                ->getFilteredUsers($username, $role);
        $resultCount = $this->entityManager->getRepository('TimeBundle:User')->getQueryCount($query);
        $paginator = new Paginator($resultCount);
        $maxPages = $paginator->getMaxPage();
        
        if($page > $maxPages || $page < 1) {
            throw new TimeBundleException(Exceptions::CODE_PAGE_NUM_NOT_FOUND);
        }
        $offest = $paginator->getOffest($page);
        $users = $this->entityManager->getRepository('TimeBundle:User')->getUsers($query, $offest, $limit);
        return [
            'users' => $users,
            'maxPages' => $maxPages,
            'currentPage' => $page
        ];
    }
    public function denyAccessUnlessShowChildrenGranted($user, $motherId)
    {
        if( $user->getRole() === Roles::ROLE_ADMIN && 
                $this->entityManager->getRepository('TimeBundle:User')->checkMotherId($motherId)) {
            return TRUE;
        }elseif ( $user->getRole() === Roles::ROLE_MOTHER && $motherId == $user->getId()) {
            return TRUE;
        } 
        throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
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
        throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
    }
}
