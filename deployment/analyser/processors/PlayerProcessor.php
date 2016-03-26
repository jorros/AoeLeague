<?php

class PlayerProcessor
{
    private $match;
    private $db;

    function __construct($match, $db)
    {
        $this->match = $match;
        $this->db = $db;
    }

    function process() {
        $collection = $this->db->selectCollection("player");

        foreach($this->match->raw->players as $index => $player) {
            $playerEntity = $collection->findOne(["name" => $player->name]);

            if($playerEntity == null) {
                $playerEntity = new Player();
                $playerEntity->name = $player->name;
                $playerEntity->_id = new MongoDB\BSON\ObjectID();

                $collection->insertOne($playerEntity);
            }
        }
    }
}