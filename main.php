<?php

require_once("ClassFactory.php");

$factory = ClassFactory::getFactory();

$player1 = $factory->get("player", 1, "C:/dev/php-7.1/php.exe Strategies/Simple/simple.php");
$player2 = $factory->get("player", 2, "C:/dev/php-7.1/php.exe Strategies/Simple/simple.php");
$game = $factory->get("game", $player1, $player2);

$winner = $game->startGame($gameHistory);
$gameHistory->saveToFile("history.txt");
echo "Winner: ".$winner->id."\n";