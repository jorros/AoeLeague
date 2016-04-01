<?php

class BaseProcessor
{
    /** @var \MongoDB\Database $object */
    protected $db;

    /** @var ProcessMatch $object */
    protected $match;

    function __construct($db, $match)
    {
        $this->db = $db;
        $this->match = $match;
    }

    protected function getPlayerId($playerName) {
        $collection = $this->db->selectCollection("player");
        $player = $collection->findOne([ "name" => $playerName ]);

        return $player->_id;
    }
}