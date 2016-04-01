<?php

class Processor
{
    /** @var \MongoDB\Database $object */
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    function getUnprocessed() {
        $collection = $this->db->selectCollection("processMatch");
        $unprocessed = array();

        foreach(glob("../unprocessed/*.mgz") as $filepath) {
            $filename = basename($filepath);
            $match = $collection->findOne(["filename" => $filename]);

            if($match != null && !$match->pause) {
                $match->raw = $this->getRaw($filename);

                $unprocessed[] = $match;
            } else if($match == null) {
                $match = new ProcessMatch();
                $match->_id = new MongoDB\BSON\ObjectID();
                $match->raw = $this->getRaw($filename);
                $match->players = array();
                $match->filename = $filename;

                $loser = null;
                $winner = null;
                foreach($match->raw->players as $player) {
                    if($player->resignTime > 0) {
                        $loser = $player->name;
                    } else {
                        $winner = $player->name;
                    }

                    $match->players[] = $player->name;
                }

                if($loser == null) {
                    $match->pause = true;
                    $collection->insertOne($match);
                } else {
                    $match->pause = false;
                    $match->winner = $winner;

                    $unprocessed[] = $match;
                }
            }
        }

        return $unprocessed;
    }
    
    function cleanUp($match, $matchId) {
        $collection = $this->db->selectCollection("processMatch");

        $collection->deleteOne(["_id" => $match->_id]);

        rename("../unprocessed/" . $match->filename, "../processed/" . $matchId . ".mgz");
    }

    private function getRaw($filename) {
        $ra = new RecAnalyst\RecAnalyst();

        $ra->load("../unprocessed/" . $filename, fopen("../unprocessed/" . $filename, "r"));
        $ra->analyze();

        return $ra;
    }
}