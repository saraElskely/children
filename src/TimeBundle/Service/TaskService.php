<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\Task;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;
use TimeBundle\Utility\Date;

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
    
    public function getTodayChildTasks( $childId)
    {
        $todayAsSchedule = Date::getTodayInWeek();
        
        $motherId = $this->entityManager
                ->getRepository('TimeBundle:User')
                ->getMotherId($childId);
        
        $allTodayTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getTodayChildTasks($todayAsSchedule, $motherId);
        
        
        
        return $this->prepareTasksArray($allTodayTasks);
        
//        $doneSchedules = $this->entityManager->getRepository('TimeBundle:dailySchedule')
//                ->getChildTodaySchedule($childId);
//    
//        return $doneSchedules ? 
//                $this->prepareTodayTasks($allTodayTasks, $doneSchedules) : 
//                $allTodayTasks;
       
    }
    public function getWeeklyChildTasks($startDate , $childId)
    {
        
        if( $startDate === NULL) {
            $startDate =  new \DateTime();
            $startDate->modify('sunday this week');
        }
                
        $motherId = $this->entityManager
                ->getRepository('TimeBundle:User')
                ->getMotherId($childId);
        
        $weeklyTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getWeeklyChildTasks($startDate, $motherId, $childId);
       
        $weeklyTasksArray = $this->prepareTasksArray($weeklyTasks);
        
        $this->getFinalWeeklyTasks($startDate, $weeklyTasksArray);
        
    }

    public function prepareTasksArray($allTodayTasks)
    {
        $tasks = array();
        foreach ($allTodayTasks as  $index => $task) {
            $tasks[$index]= ['id' => $task[0]->getId() ,
                             'taskName' => $task[0]->getTaskName(),
                             'schedule' => $task[0]->getSchedule(),
                             'state' => $task['isDone'],
                             'date' => isset($task['date'])? $task['date'] : NULL ];

        }
//        dump($tasks);
//        die();
        return $tasks;
    }
    
    public function getFinalWeeklyTasks($startDate, $weeklyTasks)
    {
        
        $dailyTasks = array();
        $tasksId = array();
        
        for($i = 0; $i < 7; $i++){
            $date = $startDate->format('Y-m-d');
            $dailyTasks[$date] = array();
            $startDate = $startDate->modify('+1 day');  
        }
        foreach ($weeklyTasks as $task) {
            if( !isset($tasksId[$task['id']]))
            {
                $tasksId[$task['id']] =$task['id'];
            } else {
                $dailyTasks[$task['date']][$task['id']]=$task;
            }
        }
        
        dump($dailyTasks[1]=1);
        die();
        
    }

}
