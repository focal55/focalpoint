<?php

namespace AccountBundle\Entity;

use AccountBundle\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="accounts")
 */
class Account
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AccountTeam", mappedBy="account")
     */
    protected $teams;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinTable(name="accounts_users")
     */
    protected $users;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active = TRUE;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /** @ORM\Embedded(class = "Address") */
    protected $address;

    /** @ORM\Embedded(class = "Address") */
    protected $mailing;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->address = new Address();
        $this->mailing = new Address();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getMailing()
    {
        return $this->mailing;
    }

    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
    }

    public function addTeam(AccountTeam $team)
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }
    }

    public function removeTeam(AccountTeam $team)
    {
        if ($this->teams->contains($team)) {
            $this->teams->remove($team);
        }
    }

    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->remove($user);
        }
    }
}
