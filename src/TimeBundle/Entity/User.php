<?php

namespace TimeBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use TimeBundle\constant\Roles;
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
    private $username = '';

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
    private $password = '';

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $age;

    /**
     * @var int
     * 
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
        return $this->username;
    }

    public function eraseCredentials() {
        
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRoles() {
        
        if($this->role == Roles::ROLE_ADMIN){
            return ['ROLE_ADMIN'];
        }
        elseif($this->role == Roles::ROLE_MOTHER){
            return ['ROLE_MOTHER'];
        }
        else{
            return ['ROLE_CHILD'] ;
        }
    }

    public function getSalt() {
        
    }

    public function getUsername(): string {
        return $this->username;
    }


    /**
     * Get id
     *
     * @return integer
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
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
     * @return integer
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
     * Get role
     *
     * @return integer
     */
    public function getRole()
    {
        return $this->role;
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
     * Get createdTasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedTasks()
    {
        return $this->createdTasks;
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
     * Add child
     *
     * @param \TimeBundle\Entity\User $child
     *
     * @return User
     */
    public function addChild(\TimeBundle\Entity\User $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \TimeBundle\Entity\User $child
     */
    public function removeChild(\TimeBundle\Entity\User $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set mother
     *
     * @param \TimeBundle\Entity\User $mother
     *
     * @return User
     */
    public function setMother(\TimeBundle\Entity\User $mother = null)
    {
        $this->mother = $mother;

        return $this;
    }

    /**
     * Get mother
     *
     * @return \TimeBundle\Entity\User
     */
    public function getMother()
    {
        return $this->mother;
    }
}
