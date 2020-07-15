<?php

class Site implements ObjectDisplay
{
    public $id;
    public $url;

    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
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
