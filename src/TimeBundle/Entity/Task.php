<?php

namespace TimeBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Task
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"taskName",},
 *     errorPath="taskName",
 *     message="This name is already in use ")
 */
class Task
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="taskName", type="string", unique=true)
     */
    private $taskName;
    
    /**
     * @var int
     * @Assert\NotBlank()
     * daily or weekly or sunday ....
     */
    private $schedule;

    /**
     * @var User
     */
    private $creator;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $taskSchedule;


    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taskSchedule = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set taskName
     *
     * @param string $taskName
     *
     * @return Task
     */
    public function setTaskName($taskName)
    {
        $this->taskName = $taskName;

        return $this;
    }

    /**
     * Get taskName
     *
     * @return string
     */
    public function getTaskName()
    {
        return $this->taskName;
    }
    
        /**
     * Set schedule
     *
     * @param integer $schedule
     *
     * @return DailySchedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return int
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set creator
     *
     * @param User $user
     *
     * @return Task
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add taskSchedule
     *
     * @param \TimeBundle\Entity\DailySchedule $taskSchedule
     *
     * @return Task
     */
    public function addTaskSchedule(\TimeBundle\Entity\DailySchedule $taskSchedule)
    {
        $this->taskSchedule[] = $taskSchedule;

        return $this;
    }

    /**
     * Remove taskSchedule
     *
     * @param \TimeBundle\Entity\DailySchedule $taskSchedule
     */
    public function removeTaskSchedule(\TimeBundle\Entity\DailySchedule $taskSchedule)
    {
        $this->taskSchedule->removeElement($taskSchedule);
    }

    /**
     * Get taskSchedule
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaskSchedule()
    {
        return $this->taskSchedule;
    }
}
