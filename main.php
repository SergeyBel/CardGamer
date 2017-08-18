<?php

require_once("Cards/Card.php");
require_once("Cards/CardsDeck.php");

require_once("Game/Game.php");
require_once("Game/GameState.php");
require_once("Game/Round.php");

require_once("History/GameHistory.php");
require_once("History/GameHistoryItem.php");

require_once("Player/PlayerData.php");
require_once("Player/PlayerExe.php");
require_once("Player/PlayerMove.php");
require_once("Player/Player.php");

echo "Hello!\n";

$first_script = $second_script = "C:\\php\\php.exe C:\\dev\\CardGamer\\Strategies\\Simple\\simple.php";

if(count($argv) > 1)
	$first_script = $argv[1];
if(count($argv) == 3)
	$second_script = $argv[2];


$player1 = new Player(1, $first_script);
$player2 = new Player(2, $second_script);
$game = new Game($player1, $player2);
$winner = $game->startGame($gameHistory);
$gameHistory->saveToFile('history.txt');
file_put_contents("player1.txt", $player1->getLog());
file_put_contents("player2.txt", $player2->getLog());

echo "Winner: $winner->id\nReason: $gameHistory->winReason\n";
