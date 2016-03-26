<?php

class LeagueProcessor
{
    private $match;

    /** @var \MongoDB\Database $object */
    private $db;

    private $config;

    function __construct($match, $db, $config)
    {
        $this->match = $match;
        $this->db = $db;
        $this->config = $config;
    }

    function process() {
        $collection = $this->db->selectCollection("league");
        $league = $collection->findOne(["_id" => $this->config->get("League")]);

        foreach($this->match->players as $player) {
            $playerObj = null;
            $key = null;

            foreach($league->players as $k => $value) {
                if($value->player == $player) {
                    $playerObj = $value;
                    $key = $k;
                    break;
                }
            }

            if($playerObj == null) {
                $playerObj = new PlayerInLeague();
                $playerObj->player = $player;
                $key = sizeof($league->players);
            }

            if($this->match->winner == $player) {
                $playerObj->gamesWon++;
            } else {
                $playerObj->gamesLost++;
            }

            $league->players[$key] = $playerObj;
        }

        $league->players = $league->players->bsonSerialize();
        usort($league->players, function($x, $y)
        {
            if($x->gamesWon == $y->gamesWon) {
                return $x->gamesLost - $y->gamesLost;
            }

            return $y->gamesWon - $x->gamesWon;
        });

        for($i = 0; $i < sizeof($league->players); $i++) {
            $league->players[$i]->rank = $i + 1;
        }

        $collection->replaceOne(["_id" => $league->_id], $league, ["upsert" => true]);
    }
}