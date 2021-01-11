<?php

namespace App\Entity;

/**
 * Description of Portfolio
 *
 * @author Piotr Zakrzewski
 * @copyright (c) 11/2020, Piotr Zakrzewski
 * 
 */

class ContactAdmin {

    private $name;
    private $email;
    private $subject;
    private $body;
    
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getSubject() {
        return $this->subject;
    }
    
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function getBody() {
        return $this->body;
    }
    
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
}