<?php

class Game
{
  private $gameState;


  public function __construct($player1, $player2)
  {
    $this->gameState = new GameState($player1, $player2);
  }

  public function startGame(&$gameHistory)
  {
    while (!$this->isGameFinished())
    {
      $round = new Round($this->gameState);
      $round->playRound();
    }

    $gameHistory = $this->gameState->getHistory();
    $gameHistory->winner = $this->gameState->getWinner()->id;
    $gameHistory->winReason = $this->gameState->getWinReason();
    
    return $this->gameState->getWinner();
  }

  protected function isGameFinished()
  {
    return $this->gameState->getState() == GameState::STATE_GAME_FINISH;
  }
}