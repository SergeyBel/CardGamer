<?php

class PlayerData
{
  public $trumpCard;
  public $deckSize;
  public $roundNumber;
  public $handCards;
  public $moveType;
  public $tableDiscardedPairs;
  public $enemyCard;
  
  public function __construct()
  {

  }

  public function toString() : string
  {
    return "";
  }
}