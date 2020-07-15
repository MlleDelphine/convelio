<?php

include('ObjectDisplay.php');

class Destination implements ObjectDisplay
{
    public $id;
    public $countryName;
    public $conjunction;
    public $name;
    public $computerName;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @return mixed
     */
    public function getConjunction()
    {
        return $this->conjunction;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getComputerName()
    {
        return $this->computerName;
    }

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
