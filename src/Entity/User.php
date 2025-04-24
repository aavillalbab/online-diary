<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseUser;
use dsarhoya\BaseBundle\Entity\UserKey;
use dsarhoya\BaseBundle\Model\EntityMappers\BaseUserInterface;

/**
 * User.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser implements BaseUserInterface
{
    /**
     * @ORM\OneToMany(targetEntity="dsarhoya\BaseBundle\Entity\UserKey", mappedBy="user")
     */
    private $keys;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="users")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $profile;

    public function __construct()
    {
        parent::__construct();
        $this->keys = new ArrayCollection();
    }
}
