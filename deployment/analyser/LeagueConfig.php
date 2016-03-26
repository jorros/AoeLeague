<?php

class LeagueConfig
{
    private $document;

    function __construct($db)
    {
        $collection = $db->selectCollection("config");
        $this->document = $collection->findOne();
    }

    function get($key) {
        return $this->document[$key];
    }
}