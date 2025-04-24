<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseAction;

/**
 * Action
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action extends BaseAction
{
    /**
     * @ORM\ManyToMany(targetEntity="Profile", mappedBy="actions")
     */
    private $profiles;

    public function __construct()
    {
        parent::__construct();
        $this->profiles = new ArrayCollection();
    }
}
