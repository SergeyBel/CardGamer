<?php

class Game
{
  private $gameState;


  public function __construct($player1, $player2)
  {
    $this->gameState = new GameState($player1, $player2);
  }

  public function startGame()
  {
    while (!$this->isGameFinished())
    {
      $round = new Round($this->gameState);
      $round->playRound();
    }
    return $this->gameState->getHistory();
  }

  protected function isGameFinished()
  {
    return $gameState->getStatus() == GameState::STATE_GAME_FINISH;
  }
}