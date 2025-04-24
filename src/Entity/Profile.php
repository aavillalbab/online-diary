<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    public function getActions()
    {
        // TODO: Implement getActions() method.
    }
}
