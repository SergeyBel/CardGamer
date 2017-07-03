<?php

class Round
{
  private $gameState;


  public function __construct(GameState $gameState)
  {
    $this->gameState = $gameState;
    $this->gameState->startRound();
  }

  public function playRound()
  {
    while (!$this->isRoundFinished())
    {
      $this->playMove();
    }
    $this->gameState->distributeCards();
  }

  protected function isRoundFinished()
  {
    $status = $this->gameState->getState();
    return ($status == GameState::STATE_ROUND_FINISH || $status == GameState::STATE_GAME_FINISH);
  }

  protected function playMove()
  {
    $player = $this->gameState->getMovePlayer();
    try 
    {
      $move = $player->makeMove($this->gameState->createPlayerData());
      $this->gameState->updateStateAfterPlayerMove($move);
    }
    catch(Exception $e)
    {
      $this->gameState->changeMovePlayer();
      $this->gameState->setWinner($this->gameState->getMovePlayer());
    }
    
    
  }
}