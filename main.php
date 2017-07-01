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



$player1 = new Player("cmd.exe");
$player2 = new Player("cmd.exe");
$game = new Game($player1, $player2);
$winner = $game->startGame($gameHistory);
echo $winner;