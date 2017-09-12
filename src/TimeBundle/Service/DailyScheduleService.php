<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\DailySchedule;
use TimeBundle\constant\Roles;
use TimeBundle\Utility\Date;
use TimeBundle\Entity\User;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;

/**
 * Description of CreateDailySchedule
 *
 * @author saraelsayed
 */
class DailyScheduleService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createAdminDailySchedule() {
        $children = $this->entityManager->getRepository('TimeBundle:User')->findByRole(Roles::ROLE_CHILD);
        $adminTasks = $this->entityManager->getRepository('TimeBundle:Task')
                ->getAdminTasks(Date::getTodayInWeek());

        foreach ($children as $child) {
            foreach ($adminTasks as $task) {
                $createdTask = new DailySchedule();
                $createdTask->setDate(new \DateTime());
                $createdTask->setUserInSchedule($child);
                $createdTask->setTaskInSchedule($task);

                $this->entityManager->persist($createdTask);
                $this->entityManager->flush();
            }
        }
    }

//    public function createMothersDailySchedule($motherId, $childId) {
//        $todayAsSchedule = Date::getTodayInWeek();
//        $mothersTasks = $this->entityManager->getRepository('TimeBundle:Task')
//                ->getTodayMothersTasks($todayAsSchedule);
//        foreach ($mothersTasks as $task) {
//            $motherId = $task->getCreator()->getId();
//            $childrenId = $this->entityManager
//                    ->getRepository('TimeBundle:User')
//                    ->getMotherChildrenId($motherId);
//            dump($childrenId);
//        }
//        die();
//    }

    public function getTodayMotherTasksId($motherTasks) {
        $todayMotherTasksId = array();
        $today = Date::getTodayInWeek();
        foreach ($motherTasks as $task) {
            if (($task->getSchedule() & $today) == $today) {
                $todayMotherTasksId[] = $task->getId();
            }
        }
        return $todayMotherTasksId;
    }

    public function getChildDailySchedules($childId) {
        return $this->entityManager
                        ->getRepository('TimeBundle:DailySchedule')
                        ->getChildDailySchedules($childId);
    }

    public function getChildTodaySchedule($childId) {
        return $this->entityManager
                        ->getRepository('TimeBundle:DailySchedule')
                        ->getChildTodaySchedule($childId);
    }

    public function getDayByDayDailySchedule($dailySchedules) {
        $arrayOfDailyTasks = array();
        foreach ($dailySchedules as $task) {
            $date = $task->getDate()->format('y-m-d');
            if (array_key_exists($date, $arrayOfDailyTasks)) {
                array_push($arrayOfDailyTasks[$date], $task);
            } else {
                $arrayOfDailyTasks[$date] = array($task);
            }
        }
        return $arrayOfDailyTasks;
    }

    public function changeScheduleState($childId, $taskId, $state) {
        if (!$state) {
            return $this->deleteSchedule($childId, $taskId);
        } else {
            return $this->createDailySchedule($childId, $taskId);
        }
    }

    public function createDailySchedule($childId, $taskId) {
        $child = $this->entityManager
                ->getRepository('TimeBundle:User')
                ->getUser($childId);
        $task = $this->entityManager
                ->getRepository('TimeBundle:Task')
                ->getTask($taskId);

        if (!is_null($this->entityManager->getRepository('TimeBundle:DailySchedule')
                                ->getScheduleId($childId, $taskId))) {
            return;
        } else {
            return $this->entityManager
                            ->getRepository('TimeBundle:DailySchedule')
                            ->createDailySchedule($child, $task);
        }
    }

    public function deleteSchedule($childId, $taskId) {
        $child = $this->entityManager
                ->getRepository('TimeBundle:User')
                ->getUser($childId);
        $task = $this->entityManager
                ->getRepository('TimeBundle:Task')
                ->getTask($taskId);
        if (is_null($this->entityManager->getRepository('TimeBundle:DailySchedule')
                                ->getScheduleId($childId, $taskId))) {
            throw new TimeBundleException(Exceptions::CODE_SCHEDULE_NOT_FOUND);
        }
        return $this->entityManager
                        ->getRepository('TimeBundle:DailySchedule')
                        ->deleteSchedule($child->getId(), $task->getId());
    }

    public function denyAccessUnlessGranted(User $user, $childId) {
        switch ($user->getRole()) {
            case Roles::ROLE_ADMIN :
                return TRUE;
            case Roles::ROLE_MOTHER :
                if ($this->entityManager->getRepository('TimeBundle:User')->getMotherId($childId) === $user->getId()) {
                    return TRUE;
                }
            case Roles::ROLE_CHILD :
                if ($user->getId() == $childId) {
                    return TRUE;
                }
        }
        throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
    }

}
