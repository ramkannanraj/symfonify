<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * One Groups have Many Users.
     * @ORM\OneToMany(targetEntity="User", mappedBy="groups")
     */
    private $users;

    /**
     * Initial Class
     *
     * @return void
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * GetId
     *
     * @return integer Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * GetName
     *
     * @return string name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName 
     *
     * @param string $name Group name
     *
     * @return object
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Users
     *
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * toString
     *
     * @return string Name
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Is Group name already exist"
     *
     * @param object $group
     *
     * @return bool
     */
    public function isGroupNameValid(Group $group): bool
    {
        return !$Group->fetchById($this->name);
    }
}
