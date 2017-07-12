<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\DailySchedule;
use TimeBundle\constant\Roles;

/**
 * Description of CreateDailySchedule
 *
 * @author saraelsayed
 */
class CreateDailyScheduleService {
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager ;
    }
    
    public function createAdminDailySchedule()
    {
        $children =$this->entityManager->getRepository('TimeBundle:User')->findByRole(Roles::ROLE_CHILD);
        $adminTasks = $this->entityManager->getRepository('TimeBundle:Task')->findByCreator(Roles::ROLE_MOTHER);
        
        foreach ($children as $child){
            foreach ($adminTasks as $task) {
                $createdTask = new  DailySchedule();
                $createdTask->setDate(new \DateTime());
                $createdTask->setUserInSchedule($child);
                $createdTask->setTaskInSchedule($task);

                $this->entityManager->persist($createdTask);
                $this->entityManager->flush();
        
            }
        }
    }
}
