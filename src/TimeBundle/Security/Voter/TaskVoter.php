<?php

namespace TimeBundle\Security\Voter;

use TimeBundle\Entity\Task;
use TimeBundle\Entity\User;
use TimeBundle\constant\Roles;
/**
 * Description of PostVoter
 *
 * @author saraelsayed
 */
class TaskVoter {
    const CREATE = 'new';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const SHOW = 'show';
    const INDEX = 'index';
    
    private function isValid($action, $user, $task)
    {
        return (!in_array($action, array('new', 'edit', 'delete', 'show', 'index')) ||
            ! $task instanceof Task || ! $user instanceof User ) ;
    }
    
    public function denyAccessUnlessGranted($action, $user, $task = null)
    {
        if($this->isValid($action, $user, $task)){
            switch ($action){
                case 'index'||'new':
                    if($user->getRole() === Roles::ROLE_ADMIN || $user->getRole() === Roles::ROLE_MOTHER )
                        return TRUE;
                case 'edit' || 'delete' || 'show':
                    $this->isCreator($user->getId(), $task);
                    
                    
            }
        }
    }
}
