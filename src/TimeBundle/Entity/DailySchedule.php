<?php

namespace TimeBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DailySchedule
 */
class DailySchedule
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $isDone;
    
    /**
     * @var \DateTime
     * @Assert\Range(
     *      min = "now",
     *      minMessage = "Date must be at least {{ limit }} to enter"
     * )
     */
    private $date;

    
    /**
     * @var \TimeBundle\Entity\Task
     */
    private $taskInSchedule;

    /**
     * @var \TimeBundle\Entity\User
     */
    private $userInSchedule;
    


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isDone
     *
     * @param boolean $isDone
     *
     * @return DailySchedule
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * Get isDone
     *
     * @return bool
     */
    public function getIsDone()
    {
        return $this->isDone;
    }



    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return DailySchedule
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set taskInSchedule
     *
     * @param \TimeBundle\Entity\Task $taskInSchedule
     *
     * @return DailySchedule
     */
    public function setTaskInSchedule(\TimeBundle\Entity\Task $taskInSchedule)
    {
        $this->taskInSchedule = $taskInSchedule;

        return $this;
    }

    /**
     * Get taskInSchedule
     *
     * @return \TimeBundle\Entity\Task
     */
    public function getTaskInSchedule()
    {
        return $this->taskInSchedule;
    }

    /**
     * Set userInSchedule
     *
     * @param \TimeBundle\Entity\User $userInSchedule
     *
     * @return DailySchedule
     */
    public function setUserInSchedule(\TimeBundle\Entity\User $userInSchedule)
    {
        $this->userInSchedule = $userInSchedule;

        return $this;
    }

    /**
     * Get userInSchedule
     *
     * @return \TimeBundle\Entity\User
     */
    public function getUserInSchedule()
    {
        return $this->userInSchedule;
    }
}
