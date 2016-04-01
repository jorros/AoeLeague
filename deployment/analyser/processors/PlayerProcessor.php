<?php

class PlayerProcessor extends BaseProcessor
{
    function process() {
        $collection = $this->db->selectCollection("player");

        foreach($this->match->raw->players as $index => $player) {
            $playerEntity = $collection->findOne(["name" => $player->name]);

            if($playerEntity == null) {
                $playerEntity = new Player();
                $playerEntity->name = $player->name;
                $playerEntity->_id = new \MongoDB\BSON\ObjectID();

                $collection->insertOne($playerEntity);
            }
        }
    }
}