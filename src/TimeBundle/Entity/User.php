<?php

namespace TimeBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 */
class User implements UserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $fname;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $lname;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $userName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      max = 15,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your  password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $age;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $role;
    
    /**
     * @var ArrayCollection
     */
    private  $createdTasks;
    
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $userSchedule;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mother;

    /**
     * @var \TimeBundle\Entity\User
     */
    private $children;
    
    
    public function __construct() {
        $this->createdTasks = new ArrayCollection();
    }

    public function __toString() {
        return $this->userName;
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
     * Set fname
     *
     * @param string $fname
     *
     * @return User
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return User
     */
    public function setLname($lname)
    {
        $this->lname = $lname;

        return $this;
    }

    /**
     * Get lname
     *
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return User
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set role
     *
     * @param integer $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get rule
     *
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * Get createdTask
     *
     * @return ArrayCollection
     */
    function getCreatedTasks() 
    {
        return $this->createdTasks;
    }


    /**
     * Add createdTask
     *
     * @param \TimeBundle\Entity\Task $createdTask
     *
     * @return User
     */
    public function addCreatedTask(\TimeBundle\Entity\Task $createdTask)
    {
        $this->createdTasks[] = $createdTask;

        return $this;
    }

    /**
     * Remove createdTask
     *
     * @param \TimeBundle\Entity\Task $createdTask
     */
    public function removeCreatedTask(\TimeBundle\Entity\Task $createdTask)
    {
        $this->createdTasks->removeElement($createdTask);
    }

    /**
     * Add userSchedule
     *
     * @param \TimeBundle\Entity\DailySchedule $userSchedule
     *
     * @return User
     */
    public function addUserSchedule(\TimeBundle\Entity\DailySchedule $userSchedule)
    {
        $this->userSchedule[] = $userSchedule;

        return $this;
    }

    /**
     * Remove userSchedule
     *
     * @param \TimeBundle\Entity\DailySchedule $userSchedule
     */
    public function removeUserSchedule(\TimeBundle\Entity\DailySchedule $userSchedule)
    {
        $this->userSchedule->removeElement($userSchedule);
    }

    /**
     * Get userSchedule
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserSchedule()
    {
        return $this->userSchedule;
    }

    /**
     * Add mother
     *
     * @param \TimeBundle\Entity\User $mother
     *
     * @return User
     */
    public function addMother(\TimeBundle\Entity\User $mother)
    {
        $this->mother[] = $mother;

        return $this;
    }

    /**
     * Remove mother
     *
     * @param \TimeBundle\Entity\User $mother
     */
    public function removeMother(\TimeBundle\Entity\User $mother)
    {
        $this->mother->removeElement($mother);
    }

    /**
     * Get mother
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMother()
    {
        return $this->mother;
    }

    /**
     * Set children
     *
     * @param \TimeBundle\Entity\User $children
     *
     * @return User
     */
    public function setChildren(\TimeBundle\Entity\User $children = null)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return \TimeBundle\Entity\User
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        
    }

    public function getSalt() {
        
    }

}
