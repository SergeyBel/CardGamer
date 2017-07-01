<?php
use Game\Game;


$player1 = new Player();
$player2 = new Player();
$game = new Game($player1, $player2);
$winner = $game->startGame($gameHistory);
echo $winner;