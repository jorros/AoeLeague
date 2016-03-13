<?php
spl_autoload_register(function ($class) {
    if (substr($class, 0, 11) === 'RecAnalyst\\') {
        $f = 'recanalyst/' . str_replace('RecAnalyst\\', '', $class) . '.php';
        if (file_exists($f)) include($f);
    }
});
require_once __DIR__ . "/vendor/autoload.php";
require_once "Player.php";

class Runner {
    private $ra;
    private $mongo;
    private $db;
    private $league = "S1";

    function __construct() {
        $this->ra = new RecAnalyst\RecAnalyst();
        $this->mongo = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->mongo->selectDatabase("League");
    }

    function processPlayers() {
        $collection = $this->db->selectCollection("player");

        $playerLost = "";
        foreach($this->ra->players as $index => $player) {
            if($player->resignTime > 0) {
                $playerLost = $index;
            }
        }

        if($playerLost == "") {
            echo "Could not find Winner/Loser. Please type in index of Loser (";
            foreach($this->ra->players as $index => $player) {
                echo " [" . $index . "]: " . $player->name . " ";
            }
            echo ")\n";
            $playerLost = readline();
        }

        foreach($this->ra->players as $index => $player) {
            $playerEntity = $collection->findOne(["name" => $player->name, "league" => $this->league]);

            if($playerEntity == null) {
                $playerEntity = new Player();
                $playerEntity->league = $this->league;
                $playerEntity->name = $player->name;
                $playerEntity->_id = new MongoDB\BSON\ObjectID();
            }

            if($playerLost == $index) {
                $playerEntity->gamesLost++;
            } else {
                $playerEntity->gamesWon++;
            }

            $collection->replaceOne(["_id" => $playerEntity->_id], $playerEntity, ["upsert" => true]);
        }
    }

    function process() {
        $records = scandir("unprocessed");
        foreach($records as $recorded) {
            $info = new SplFileInfo($recorded);

            if($info->getExtension() == "mgz") {
                $path = "unprocessed/" . $info->getFilename();

                $this->ra->load($path, fopen($path, "r"));
                $this->ra->analyze();

                $this->processPlayers();

                rename($path, "processed/" . $info->getFilename());
                echo "Processed " . $info->getFilename() . "\n";

                $this->ra->reset();
//        $ra->config->mapHeight = 512;
//        $ra->config->mapWidth = 1024;
//        imagejpeg($ra->generateMap(), "test.jpg");
            }
        }
    }
}

$runner = new Runner();
$runner->process();