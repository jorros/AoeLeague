<?php

class LeagueConfig
{
    public $league;
    public $leg;

    /**
     * @param MongoDB\Database $db
     */
    function __construct($db)
    {
        $collection = $db->selectCollection("config");
        $document = $collection->findOne();
        
        $this->league = $document->league;
        $this->leg = $document->leg;
    }
}