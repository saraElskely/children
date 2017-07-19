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
class DailyScheduleService {
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager ;
    }
    
    public function createAdminDailySchedule()
    {
        $children =$this->entityManager->getRepository('TimeBundle:User')->findByRole(Roles::ROLE_CHILD);
        $adminTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getAdminTasks($this->getTodayInWeek());

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
    
    public function createMothersDailySchedule( $motherId, $childId )
    {
        $todayAsSchedule = $this->getTodayInWeek() ;
        $mothersTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getTodayMothersTasks($todayAsSchedule);
        foreach ($mothersTasks as $task) {
            $motherId = $task->getCreator()->getId();
            $childrenId = $this->entityManager
                    ->getRepository('TimeBundle:User')
                    ->getMotherChildrenId($motherId);
            dump($childrenId);
        }
        die();
//        $todayMotherTasksId = $motherTasks ? $this->getTodayMotherTasksId($motherTasks): NULL;
//        return $this->entityManager->getRepository('TimeBundle:DailySchedule')
//                ->createMotherDailySchedule( $todayMotherTasksId, $childId );
    }
    
    public function getTodayInWeek()
    {
        $today = new \DateTime();
        $today = strtotime($today->format('y-m-d'));
        $today = date('w', $today);
        
        return pow(2,$today);
    }
    
    public function getTodayMotherTasksId($motherTasks)
    {
        $todayMotherTasksId = array();
        $today =  $this->getTodayInWeek() ;
        foreach ($motherTasks as $task) {
            if(($task->getSchedule() & $today) == $today){
                $todayMotherTasksId[]= $task->getId();
            }
        }
        return $todayMotherTasksId;
    }

    public function getChildDailySchedules($childId)
    {
        return $this->entityManager
                ->getRepository('TimeBundle:DailySchedule')
                ->getChildDailySchedules($childId);
    }
    
    public function getChildTodaySchedule($childId)
    {
        return $this->entityManager
                ->getRepository('TimeBundle:DailySchedule')
                ->getChildTodaySchedule($childId);
    }
    
    public function getDayByDayDailySchedule($dailySchedules)
    {
        $arrayOfDailyTasks =array();
        foreach ($dailySchedules as $task){
            $date = $task->getDate()->format('y-m-d');
            if(array_key_exists($date, $arrayOfDailyTasks)){
                array_push($arrayOfDailyTasks[$date], $task);
            }
            else{
                $arrayOfDailyTasks[$date]=array($task);
            }
        }
        return $arrayOfDailyTasks;
        
    }
    
}
