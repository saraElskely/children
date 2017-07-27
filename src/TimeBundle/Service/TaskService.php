<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\Task;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;

/**
 * Description of TaskService
 *
 * @author saraelsayed
 */
class TaskService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createTask($taskName, $schedule, $creator) {
        $task = $this->entityManager->getRepository('TimeBundle:Task')->createTask($taskName, $schedule, $creator);
        return $task;
    }

    public function deleteTask($taskId) {
        return $this->entityManager->getRepository('TimeBundle:Task')->deleteTask($taskId);
    }

    public function getTasks($userId, $userRole) {
        if ($userRole == Roles::ROLE_ADMIN) {
            $tasks['mothers'] = $this->entityManager->getRepository('TimeBundle:Task')->getAllMothersTasks();
            $tasks['yourTask'] = $this->entityManager->getRepository('TimeBundle:Task')->getAdminTasks();
        } else {
            $tasks['admin'] = $this->entityManager->getRepository('TimeBundle:Task')->getAdminTasks();
            $tasks['yourTask'] = $this->entityManager->getRepository('TimeBundle:Task')->getMotherTasks($userId);
        }
        return $tasks;
    }
    
    public function getTodayChildTasks($todayAsSchedule, $motherId, $childId)
    {
        $allTodayTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getTodayChildTasks($todayAsSchedule, $motherId);
        
        $doneSchedules = $this->entityManager->getRepository('TimeBundle:dailySchedule')
                ->getChildTodaySchedule($childId);
    
        return $doneSchedules ? 
                $this->prepareTodayTasks($allTodayTasks, $doneSchedules) : 
                $allTodayTasks;
       
    }
    
    public function prepareTodayTasks($allTodayTasks, $doneSchedules)
    {
        foreach ($allTodayTasks as  $index => $task) {
            foreach ($doneSchedules as $schedule) {
                if(isset($allTodayTasks[$index])){
                    $task->getId() === $schedule['id'] ? 
                            $allTodayTasks[$index]->state= 'Done' : 
                            $allTodayTasks[$index]->state= 'notDone';
                }
            }
        }
        return $allTodayTasks;
    }

}
