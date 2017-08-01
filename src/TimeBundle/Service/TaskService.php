<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\Task;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;
use TimeBundle\Utility\Date;
use TimeBundle\constant\Schedule;

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
        
        
        
        return $this->mapTasksToArray($allTodayTasks);
        
//        $doneSchedules = $this->entityManager->getRepository('TimeBundle:dailySchedule')
//                ->getChildTodaySchedule($childId);
//    
//        return $doneSchedules ? 
//                $this->prepareTodayTasks($allTodayTasks, $doneSchedules) : 
//                $allTodayTasks;
       
    }
    public function getWeeklyChildTasks($childId, $startDate = null)
    { 
        if( $startDate === NULL) {
            $startDate = Date::getStartDateInWeek();
        }
                
        $motherId = $this->entityManager
                ->getRepository('TimeBundle:User')
                ->getMotherId($childId);
        
        $weeklyTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getWeeklyChildTasks($startDate, $motherId, $childId);
       
        $weeklyTasksArray = $this->mapTasksToArray($weeklyTasks);
        
        return $this->getFinalWeeklyTaskArray($startDate, $weeklyTasksArray);
        
    }

    public function mapTasksToArray($allTodayTasks)
    {
        $tasks = array();
        foreach ($allTodayTasks as  $index => $task) {
            $tasks[$index]= ['id' => $task[0]->getId() ,
                             'taskName' => $task[0]->getTaskName(),
                             'schedule' => $task[0]->getSchedule(),
                             'state' => $task['isDone'],
                             'date' => isset($task['date'])? $task['date']->format('Y-m-d') : false ];
        }
//        dump($tasks);
//        die();
        return $tasks;
    }
    
    

    public function getFinalWeeklyTaskArray($startDate, $weeklyTasks)
    {
        $dailyTasksArray = Date::getEmptyArrayOfDatesForWeek($startDate);
        $tasksIdArray = array();
        
        foreach ($weeklyTasks as $task){
            if( !isset($tasksIdArray[$task['id']])){
                $tasksIdArray[$task['id']] =$task['id'];
                if( $task['schedule'] == Schedule::SCHEDULE_DAILY ) {
                    $dailyTasksArray = $this->setDailyTask($dailyTasksArray, $task);    
                }
                else 
                    $dailyTasksArray = $this->setWeeklyTask($dailyTasksArray, $task, $startDate);   
            } else {
                $dailyTasksArray[$task['date']][$task['id']]=$task;
            }
        }
        return $dailyTasksArray ;
//        dump($dailyTasksArray);
//        die();
    
    }
    
    public function setDailyTask($dailyTasksArray, $task)
    {
        foreach ($dailyTasksArray as $date => $dailyTask) {
            $dailyTasksArray[$date][$task['id']] = $task;
            if( is_null($task['date']) || $date != $task['date']){
                $dailyTasksArray[$date][$task['id']]['date'] = $date;
                $dailyTasksArray[$date][$task['id']]['state'] = false;
            }
        }
        return $dailyTasksArray;
    }
    
    public function setWeeklyTask($dailyTasksArray, $task, $startDate )
    {
        $offest = Date::getDayInWeek($startDate);
        foreach (Schedule::SCHEDULE_DAYS as $day){
            if($task['schedule'] & $day){     
                $i = Date::getDayInWeekBasedOnStart($day, $offest);
                $dailyTasksArray[(new \DateTime($startDate))->modify('+'.$i.' day')->format('Y-m-d')][$task['id']]=$task;    
            }
        }
        return $dailyTasksArray;
    }

}
