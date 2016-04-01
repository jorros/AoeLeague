<?php

class LeagueProcessor extends BaseProcessor
{
    /** @var LeagueConfig $object */
    private $config;

    function __construct($match, $db, $config)
    {
        parent::__construct($db, $match);
        $this->config = $config;
    }

    function process() {
        $collection = $this->db->selectCollection("league");
        $league = $collection->findOne(["_id" => $this->config->league['$id']]);

        foreach($this->match->players as $playerName) {
            $playerObj = null;
            $key = null;
            $id = $this->getPlayerId($playerName);

            foreach($league->players as $k => $value) {
                if($value->player['$id']->__toString() == $id->__toString()) {
                    $playerObj = $value;
                    $key = $k;
                    break;
                }
            }

            if($playerObj == null) {
                $playerObj = new PlayerInLeague();
                $playerObj->player = DBRef::create("player", $this->getPlayerId($playerName));
                $key = sizeof($league->players);
            }

            if($this->match->winner == $playerName) {
                $playerObj->gamesWon++;
            } else {
                $playerObj->gamesLost++;
            }

            $stat = $this->match->raw->postgameData->players[$playerName->index - 1]->militaryStats;


            $playerObj->kd = (float)($stat->unitsKilled + $stat->buildingsRazed) / ($stat->unitsLost + $stat->buildingsLost);

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