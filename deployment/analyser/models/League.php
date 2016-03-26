<?php

class League
{
    public $_id;
    public $_class = "de.jorros.league.models.League";

    public $name; // string
    public $gameSettings; // gameSettings
    public $mode; // string
    public $players; // list<PlayerInLeague>
}