<?php

class Player
{
  private $hand;
  private $filePath;
  

  public function __construct($filePath)
  {
    $hand = new CardsArray();
    $this->filePath = $filePath;
  }

  public function makeMove($playerData)
  {
    $stdinStr = $this->dataToStr($this->hand, $playerData);
    return $this->call($stdinStr);
  }

  public function dataToStr($hand, $playerData)
  {
    // make string to get in strategy program stdin
  }

  protected function call($str)
  {
    //need overrired to strategy programs on different languages
    //call startegy program, get answer and return PlayerMove class
  }

  public function setCards($cards)
  {
    $this->hand = $cards;
  }
}