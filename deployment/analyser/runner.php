<?php
spl_autoload_register(function ($class) {
    if (substr($class, 0, 11) === 'RecAnalyst\\') {
        $f = 'recanalyst/' . str_replace('RecAnalyst\\', '', $class) . '.php';
        if (file_exists($f)) include($f);
    }
});
require_once __DIR__ . "/vendor/autoload.php";

foreach (glob("models/*.php") as $filename)
{
    include $filename;
}
include "Processor.php";
include "LeagueConfig.php";
foreach (glob("processors/*.php") as $filename)
{
    include $filename;
}

$mongo = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongo->selectDatabase("League");
$processor = new Processor($db);
$config = new LeagueConfig($db);

$unprocessed = $processor->getUnprocessed();

foreach($unprocessed as $match) {
    $playerProc = new PlayerProcessor($match, $db);
    $playerProc->process();

    $leagueProc = new LeagueProcessor($match, $db, $config);
    $leagueProc->process();

    rename("../unprocessed/" . $match->filename, "../processed/" . $match->filename);
}