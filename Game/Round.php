<?php

class Round
{
  private $gameState;


  public function __construct($gameState)
  {
    $this->gameState = $gameState;
    $this->gameState->startRound();
  }

  public function playRound()
  {
    $gameState->distributeCards();
    while (!$this->isRoundFinished())
    {
      $this->playMove();
    }
  }

  protected function isRoundFinished()
  {
    $status = $gameState->getStatus();
    return ($status == GameState::STATE_ROUND_FINISH || $status == GameState::STATE_GAME_FINISH);
  }

  protected function playMove()
  {
    $player = $this->gameState->getMovePlayer();
    $move = $player->makeMove($this->gameState->createPlayerData());
    $gameState->updateStateAfterPlayerMove($move);
  }
}