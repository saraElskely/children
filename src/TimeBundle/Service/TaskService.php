<?php

namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\Entity\Task;
use TimeBundle\constant\Roles;

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

}
