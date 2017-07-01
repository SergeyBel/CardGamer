<?php
require_once('Game\GameState.php');

class Game
{
  private $gameState;


  public function __construct($player1, $player2)
  {
    $this->gameState = new GameState($player1, $player2);
  }

  public function startGame(&$gameStory)
  {
    while (!$this->isGameFinished())
    {
      $round = new Round($this->gameState);
      $round->playRound();
    }

    $gameHistory = $this->gameState->getHistory();

    return $this->gameState->getWinner();
  }

  protected function isGameFinished()
  {
    return $gameState->getStatus() == GameState::STATE_GAME_FINISH;
  }
}