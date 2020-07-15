<?php

class Destination implements ObjectDisplay
{
    public $id;
    public $countryName;
    public $conjunction;
    public $name;
    public $computerName;

    public function __construct($id, $countryName, $conjunction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }

    public function renderHtml()
    {
        return '<p>' . $this->id . '</p>';
    }

    public function renderText()
    {
        return $this->id;
    }
}
