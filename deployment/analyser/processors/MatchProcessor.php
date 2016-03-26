<?php

class MatchProcessor
{
    /** @var ProcessMatch $object */
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
        $matchObj = new Match();
        $matchObj->_id = new \MongoDB\BSON\ObjectID();

        $matchObj->league = $this->config->get("League");
        $matchObj->leg = $this->config->get("Leg");

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
    }

    /**
     * @param \RecAnalyst\Player $player
     * @return PlayerMatch
     */
    private function processPlayer($player) {
        $playerCol = $this->db->selectCollection("player");
        $playerDb = $playerCol->findOne(["name" => $player->name]);

        $playerIndex = 1;
        foreach($this->match->raw->playersByIndex as $index => $value) {
            if($value->name == $player->name) {
                $playerIndex = $index;
            }
        }

        $playerMatch = new PlayerMatch();
        $playerMatch->player = $playerDb->_id;
        $playerMatch->civilization = $player->civId;
        $playerMatch->feudalTime = $player->feudalTime;
        $playerMatch->castleTime = $player->castleTime;
        $playerMatch->imperialTime = $player->imperialTime;
        $playerMatch->research = $player->researches;
        $playerMatch->resignTime = $player->resignTime;
        $playerMatch->colour = $player->colorId;
        $playerMatch->buildings = $this->match->raw->buildings[$playerIndex];

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