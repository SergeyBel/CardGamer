<?php

class Player
{
  private $filePath;


  public function __construct($filePath)
  {
    $this->filePath = $filePath;
  }

  public function makeMove(PlayerData $playerData): PlayerMove
  {
    $stringData = $playerData->toString();
    $stringMove = $this->interactWithPlayer($stringData);
    $move = PlayerMove::fromString($stringMove);
    return $move;
  }


  protected function interactWithPlayer(string $str): string
  {
    return "";
  }
}

