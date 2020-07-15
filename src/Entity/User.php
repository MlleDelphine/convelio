<?php

class User implements ObjectDisplay
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public function __construct($id, $firstname, $lastname, $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function renderHtml()
    {
        return "<p> $this->firstname $this->lastname </p>";
    }

    public function renderText()
    {
        return  "$this->firstname $this->lastname";
    }
}
