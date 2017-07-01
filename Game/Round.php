<?php

class Round
{
  private $gameState;


  public function __construct($gameState)
  {
    $this->gameState = $gameState;
  }

  public function playRound()
  {
    $this->distributeCards();
    while (!$this->isroundFinished())
    {
      $this->playMove();
    }
  }

  protected function isRoundFinished()
  {
    return $gameState->getStatus() == GameState::STATE_ROUND_FINISH;
  }

  protected function distribureCards()
  {

  }

  protected function playMove()
  {
    $player = $this->gameState->getMovePlayer();
    $move = $player->makeMove();
    $gameState->updateStateAfterPlayerMove($move);
  }

}