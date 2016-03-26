<?php

class ProcessMatch
{
    public $_id;
    public $_class = "de.jorros.league.models.ProcessMatch";

    public $filename;
    public $players; // list<string>
    public $winner;
    public $pause;

    /** @var \RecAnalyst\RecAnalyst $object */
    public $raw;
}