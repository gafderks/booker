<?php
// src/Subscription.php

/**
 * Class Subscription
 *
 * @Entity
 */
class Subscription
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
    protected $email;
    
    /**
     * @ManyToOne(targetEntity="Team", inversedBy="subscriptions", cascade={"persist"})
     * @JoinColumn(name="team_id", referencedColumnName="id")
     * @var \Team
     */
    private $team;
    
    /**
     * @var array
     * @Column(type="array")
     */
    protected $names;
    
    /**
     * @Column(type="datetime")
     */
    protected $timestamp;
    
    /**
     * @Column(type="string")
     */
    protected $pass;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNames() {
        return $this->names;
    }
    
    public function getTimestamp() {
        return $this->timestamp;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getPass() {
        return $this->pass;
    }
    
    public function getTeam() {
        return $this->team;
    }
    
    public function __construct($names, $email, \Team $team) {
        $this->names = array_values(array_filter($names));
        $this->email = $email;
        $this->timestamp = new \DateTime('now');
        $this->pass = sha1(uniqid());
        $this->team = $team;
    }
    
    public function update($names) {
        $this->names = array_values(array_filter($names));
    }
    
}