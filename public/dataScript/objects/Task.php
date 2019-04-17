<?php


class Task
{
    public $name;
    public $description;
    public $status;
    public $assignedUser;
    public $assignedBy;
    public $impact;
    public $category;
    public $priority;
    public $comments;
    public $timeToSolve;
    public $createdAt;
    public $updatedAt;
    public $deletedAt;



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAssignedUser()
    {
        return $this->assignedUser;
    }

    /**
     * @param mixed $assignedUser
     */
    public function setAssignedUser($assignedUser)
    {
        $this->assignedUser = $assignedUser;
    }

    /**
     * @return mixed
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }

    /**
     * @param mixed $assignedBy
     */
    public function setAssignedBy($assignedBy)
    {
        $this->assignedBy = $assignedBy;
    }

    /**
     * @return mixed
     */
    public function getImpact()
    {
        return $this->impact;
    }

    /**
     * @param mixed $impact
     */
    public function setImpact($impact)
    {
        $this->impact = $impact;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getTimeToSolve()
    {
        return $this->timeToSolve;
    }

    /**
     * @param mixed $timeToSolve
     */
    public function setTimeToSolve($timeToSolve)
    {
        $this->timeToSolve = $timeToSolve;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }


}
