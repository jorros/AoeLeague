<?php

class League
{
    public $_id;
    public $_class = "de.jorros.league.models.League";

    public $name; // string

    /** @var GameSettings $object */
    public $gameSettings; // gameSettings
    
    public $mode; // string

    /** @var PlayerInLeague[] $object */
    public $players; // list<PlayerInLeague>
}