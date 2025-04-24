<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseCompany;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company extends BaseCompany
{
    /**
     * @ORM\OneToMany(targetEntity="Profile", mappedBy="company")
     */
    private $profiles;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="company")
     */
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->profiles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }
}
