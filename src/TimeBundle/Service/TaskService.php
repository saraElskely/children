<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\Task;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;
use TimeBundle\Utility\Date;
use TimeBundle\constant\Schedule;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use TimeBundle\Utility\Paginator;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;
//use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Description of TaskService
 *
 * @author saraelsayed
 */
class TaskService {

    private $entityManager;
    const CREATE = 'new';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const SHOW = 'show';
    const INDEX = 'index';

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    private function isValid($action, $user)
    {
        return ( in_array($action, array(self::CREATE, self::DELETE, self::EDIT, self::INDEX, self::SHOW)) ||  $user instanceof User ) ;
    }

    public function denyAccessUnlessGranted($action, $user, $task = null)
    {
        if($this->isValid($action, $user)){
            switch ($action){
                case self::INDEX :
                case self::CREATE: 
                    if($user->getRole() === Roles::ROLE_ADMIN || $user->getRole() === Roles::ROLE_MOTHER ) {
                        return TRUE;
                    }
                    break;   
                case self::EDIT:
                case self::DELETE:
                case self::SHOW:
                    if(! $task && ! $task instanceof Task) {
                        throw new TimeBundleException(Exceptions::CODE_TASK_NOT_FOUND);
                    }elseif ($user->getId() === $task->getCreator()->getId()) {
                         return TRUE ;
                    }
            }
        }
        throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
    }

    public function createTask($taskName, $schedule, $creator) 
    {
        return $this->entityManager->getRepository('TimeBundle:Task')->createTask($taskName, $schedule, $creator);    
    }
    
    public function getTask($taskId)
    {
        return $this->entityManager->getRepository('TimeBundle:Task')->getTask($taskId);
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
    }
    
    public function getWeeklyChildTasks($childId, $page = 1)
    { 
        if( !is_numeric($page) || $page < 1) {
            throw new TimeBundleException(Exceptions::CODE_PAGE_NUM_NOT_FOUND);
        }
      
        $startDate = Date::getStartDateInWeek( $page);
        $firstDateToUser = $this->entityManager
                ->getRepository('TimeBundle:DailySchedule')
                ->getFirstDataToUser($childId);
        if( is_null($firstDateToUser) || strtotime($startDate) < strtotime($firstDateToUser)) {
            $startDate = Date::getStartDateInWeek();
        }
        
        $motherId = $this->entityManager
                ->getRepository('TimeBundle:User')
                ->getMotherId($childId);
        
        $weeklyTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getWeeklyChildTasks($startDate, $motherId, $childId);
       
//        $weeklyTasksArray = $this->mapTasksToArray($weeklyTasks);
        
        return $this->getFinalWeeklyTaskArray($startDate, $weeklyTasks);
        
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
//        dump($dailyTasksArray);
//        die();
        return $dailyTasksArray ;
        
    
    }
    
    public function setDailyTask($dailyTasksArray, $task)
    {
        foreach ($dailyTasksArray as $date => $dailyTask) {
            $dailyTasksArray[$date][$task['id']] = $task;
            if( is_null($task['date']) || $date != $task['date']){
                $dailyTasksArray[$date][$task['id']]['date'] = $date;
                $dailyTasksArray[$date][$task['id']]['is_done'] = false;
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
    
    public function getFilteredTasks($taskName = null, $schedule =-1, $page=1, $limit= 2)
    {
        $query = $this->entityManager->getRepository('TimeBundle:Task')
                ->getFilteredTasksQuery($taskName, $schedule);
        
        $resultCount = $this->entityManager->getRepository('TimeBundle:Task')->getQueryCount($query);
        $paginator = new Paginator($resultCount);
        $maxPages = $paginator->getMaxPage();
        $offest = $paginator->getOffest($page);
        $tasks = $this->entityManager->getRepository('TimeBundle:Task')->getTasks($query, $offest, $limit);

        return [
            'tasks' => $tasks,
            'maxPages' => $maxPages,
            'currentPage' => $page
        ];
        
    }

}
