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
     * @var string
     * @Column(type="string")
     */
    protected $speltak;
    
    /**
     * @var string
     * @Column(type="string")
     */
    protected $time;
    
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
    
    public function getSpeltak() {
        return $this->speltak;
    }
    
    public function getTime() {
        return $this->time;
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
    
    public function __construct($time, $names, $speltak, $email) {
        $this->time = $time;
        $this->names = array_values(array_filter($names));
        $this->speltak = $speltak;
        $this->email = $email;
        $this->timestamp = new \DateTime('now');
        $this->pass = sha1(uniqid());
    }
    
    public function update($names, $speltak) {
        $this->names = array_values(array_filter($names));
        $this->speltak = $speltak;
    }
    
}