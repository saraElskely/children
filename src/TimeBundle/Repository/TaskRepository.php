<?php

namespace TimeBundle\Repository;

use TimeBundle\constant\Roles;
use TimeBundle\Entity\Task;
use TimeBundle\Entity\User;
use TimeBundle\constant\Schedule;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository {

    public function createTask($taskName, $schedule, $creator) {
        $task = new Task();
        $task->setTaskName($taskName)
                ->setCreator($creator)
                ->setSchedule($schedule);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($task);
        $entityManager->flush();
        return $task;
    }

    public function updateTask($taskId, $taskName, $schedule) {
        return $this->createQueryBuilder('task')
                        ->update()
                        ->set('task.taskName', $taskName)
                        ->set('task.schedule', $schedule)
                        ->where("task.id = :taskId")
                        ->setParameter('taskId', $taskId)
                        ->getQuery()
                        ->execute();
    }

    public function getTask($taskId) {
        $task = $this->findOneById($taskId);
        if (!isset($task)) {
            throw new TimeBundleException(Exceptions::CODE_TASK_NOT_FOUND);
        }

        return $task;
    }

    public function deleteTask($taskId) {
        $this->createQueryBuilder('task')
                ->delete()
                ->where("task.id = :taskId")
                ->setParameter('taskId', $taskId)
                ->getQuery()
                ->execute();
    }

    public function getAdminTasks() {
        $adminId = $this->getEntityManager()->getRepository('TimeBundle:User')->getAdminId();
        return $this->createQueryBuilder('task')
                        ->select()
                        ->where("task.creator = :adminId")
                        ->setParameter('adminId', $adminId)
                        ->getQuery()
                        ->execute();
    }

    public function getTodayChildTasks($todayAsSchedule, $motherId, $childId) {
        $today = (new \DateTime())->format('Y-m-d');

        $adminId = $this->getEntityManager()->getRepository('TimeBundle:User')->getAdminId();
       
        return $this->createQueryBuilder('task')
                        ->select('task , schedule.isDone')
                        ->leftJoin('TimeBundle:DailySchedule', 'schedule', 'WITH', " schedule.date = :today AND schedule.userInSchedule = :childId AND task.id = schedule.taskInSchedule")
                        ->setParameter('today', $today)
                        ->setParameter('childId', $childId)
                        ->where("task.schedule = :daily OR task.schedule = :todayAsSchedule")
                        ->setParameter('daily', Schedule::SCHEDULE_DAILY )
                        ->setParameter('todayAsSchedule', $todayAsSchedule)
                        ->andWhere("task.creator = :adminId OR task.creator = :motherId")
                        ->setParameter('adminId', $adminId)
                        ->setParameter('motherId', $motherId)
                        ->getQuery()
                        ->execute();
    }

    public function getWeeklyChildTasks($startDate, $motherId, $childId) {
        $endDate = (new \DateTime($startDate))->modify('+1 week')->format('Y-m-d');
        dump($startDate);
        dump($endDate);
        $adminId = $this->getEntityManager()->getRepository('TimeBundle:User')->getAdminId();
//        SELECT t.* ,s.is_done ,s.date
//        FROM task AS t
//        LEFT JOIN daily_schedule AS s 
//        ON t.id = s.taskId  AND (s.date BETWEEN '2017-07-28' AND '2017-08-04') AND s.userId = 23
//        WHERE t.user IN (10 ,7)
        $sql = "SELECT t.* ,s.is_done ,s.date FROM task AS t LEFT JOIN daily_schedule AS s ON t.id = s.taskId  AND (s.date BETWEEN :start AND :end) AND s.userId = :childId WHERE t.user IN (:adminId ,:motherId)";
        $connection = $this->getEntityManager()->getConnection();
        $query = $connection->prepare($sql);
        $query->bindValue('start', $startDate);
        $query->bindValue('end', $endDate);
        $query->bindValue('childId', $childId);
        $query->bindValue('motherId', $motherId);
        $query->bindValue('adminId', $adminId);
        $query->execute();
        return $query->fetchAll();
//        dump(  $this->createQueryBuilder('task')
//                ->select('task, schedule.isDone, schedule.date')
//                ->leftJoin('TimeBundle:DailySchedule', 'schedule','WITH',
//                        "task.id = schedule.taskInSchedule AND (schedule.date BETWEEN '$start' AND '$end') AND schedule.userInSchedule = $childId")
//                ->Where("task.creator IN ($adminId,  $motherId)")
//                ->getQuery()
//                ->execute());  
    }

    public function getMotherTasks($motherId) {
        return $this->createQueryBuilder('task')
                        ->select()
                        ->where("task.creator = :motherId")
                        ->setParameter('motherId', $motherId)
                        ->getQuery()
                        ->execute();
    }

    public function getAllMothersTasks() {
        $role = Roles::ROLE_MOTHER;
        $subquery = $this->getEntityManager()->getRepository('TimeBundle:User')->createQueryBuilder('user')
                ->select('user.id')
                ->where("user.role = $role")
                ->getDQL();

        $qb = $this->createQueryBuilder('task');
        $mothersTasks = $this->createQueryBuilder('task')->select()
                ->where($qb->expr()->in('task.creator', $subquery))
                ->getQuery()
                ->getResult();

        return $mothersTasks;
    }

    public function getFilteredTasksQuery($taskName, $schedule) {
        $query = $this->createQueryBuilder('task')
                ->select('task, user.username')
                ->join('TimeBundle:User', 'user', 'WITH', "task.creator = user.id");
        if (!is_null($taskName) || $taskName !== '') {
            $query->where("task.taskName LIKE '%$taskName%'");
        }
        $schedule == 0 ? $query->andWhere("task.schedule = $schedule") : $schedule != -1 ?
                                $query->andWhere("BIT_AND(task.schedule, $schedule) = $schedule") : $query;

        return $query;
    }

    public function getQueryCount($query) {
        $alias = $query->getRootAliases();
        $q = clone $query;
        return $q
                        ->select("count($alias[0].id)")
                        ->getQuery()
                        ->getSingleScalarResult()
        ;
    }

    public function getTasks($query, $offest = 1, $limit = 2) {
        return $query
                        ->setFirstResult($offest)
                        ->setMaxResults($limit)
                        ->getQuery()
                        ->getArrayResult();
    }

}
