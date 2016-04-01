<?php

class MatchProcessor extends BaseProcessor
{
    /** @var LeagueConfig $object */
    private $config;

    function __construct($match, $db, $config)
    {
        parent::__construct($db, $match);
        $this->config = $config;
    }

    function process() {
        $matchObj = new Match();
        $matchObj->_id = new \MongoDB\BSON\ObjectID();

        $matchObj->league = $this->config->league['$id'];
        $matchObj->leg = $this->config->leg;

        $matchObj->playTime = $this->match->raw->gameInfo->playTime;

        $matchObj->gameSettings = $this->loadGameSettings();
        $matchObj->players = array();

        foreach($this->match->raw->players as $player) {
            $matchObj->players[] = $this->processPlayer($player);
        }

        $collection = $this->db->selectCollection("match");
        $collection->insertOne($matchObj);

        $this->match->raw->config->mapHeight = 512;
        $this->match->raw->config->mapWidth = 1024;
        $gd = $this->match->raw->generateMap();

        imagepng($gd, "../maps/" . $matchObj->_id . ".png");
        imagedestroy($gd);

        return $matchObj->_id;
    }

    /**
     * @param \RecAnalyst\Player $player
     * @return PlayerMatch
     */
    private function processPlayer($player) {
        $playerIndex = 1;
        foreach($this->match->raw->playersByIndex as $index => $value) {
            if($value->name == $player->name) {
                $playerIndex = $index;
            }
        }

        $playerMatch = new PlayerMatch();
        $playerMatch->player = DBRef::create("player", $this->getPlayerId($player->name));
        $playerMatch->civilization = $player->civId;
        $playerMatch->feudalTime = $player->feudalTime;
        $playerMatch->castleTime = $player->castleTime;
        $playerMatch->imperialTime = $player->imperialTime;
        $playerMatch->research = $player->researches;
        $playerMatch->resignTime = $player->resignTime;
        $playerMatch->colour = $player->colorId;
        $playerMatch->buildings = $this->match->raw->buildings[$playerIndex];

        $stat = $this->match->raw->postgameData->players[$playerIndex - 1];
        $playerMatch->winner = $stat->victory;
        $playerMatch->mvp = $stat->mvp;
        $playerMatch->totalScore = $stat->totalScores[0];
        $playerMatch->militaryStats = $stat->militaryStats;
        $playerMatch->economyStats = $stat->economyStats;
        $playerMatch->techStats = $stat->techStats;
        $playerMatch->societyStats = $stat->societyStats;

        return $playerMatch;
    }

    /**
     * @return GameSettings
     */
    private function loadGameSettings() {
        $gameSettings = new GameSettings();
        $gameSettings->gameType = $this->match->raw->gameSettings->gameType;
        $gameSettings->mapStyle = $this->match->raw->gameSettings->mapStyle;
        $gameSettings->gameSpeed = $this->match->raw->gameSettings->gameSpeed;
        $gameSettings->revealMap = $this->match->raw->gameSettings->revealMap;
        $gameSettings->mapSize = $this->match->raw->gameSettings->mapSize;
        $gameSettings->scenario = $this->match->raw->gameSettings->isScenario();
        $gameSettings->mapName = $this->match->raw->gameSettings->getMapName();
        $gameSettings->popLimit = $this->match->raw->gameSettings->popLimit;

        return $gameSettings;
    }
}