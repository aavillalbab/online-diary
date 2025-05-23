<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseProfile;
use dsarhoya\BaseBundle\Model\EntityMappers\BaseProfileInterface;

/**
 * Profile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile extends BaseProfile implements BaseProfileInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="profiles")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="profile")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Action", inversedBy="profiles")
     * @ORM\JoinTable(name="permissions",
     *      joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="action_id", referencedColumnName="id")}
     *      )
     */
    private $actions;

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setProfile($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfile() === $this) {
                $user->setProfile(null);
            }
        }

        return $this;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        $this->actions->removeElement($action);

        return $this;
    }
}
