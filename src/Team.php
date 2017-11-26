<?php
// src/Subscription.php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Team
 *
 * @Entity
 */
class Team
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @Column(type="string")
     */
    protected $teamName;
    
    /**
     * @OneToMany(targetEntity="Subscription", mappedBy="team")
     * @var \Subscription[]
     */
    private $subscriptions;
    
    public function getId() {
        return $this->id;
    }
    
    public function getSubscriptions() {
        return $this->subscriptions;
    }
    
    public function getTeamName() {
        return $this->teamName;
    }
    
    public function __construct($teamName) {
        $this->subscriptions = new ArrayCollection();
        $this->teamName = $teamName;
    }
    
    public function update($teamName) {
        $this->teamName = $teamName;
    }
    
}