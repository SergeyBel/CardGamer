<?php

class PlayerData
{
  const TYPE_ATTACK = 1;
  const TYPE_DEFEND = 2;

  public $trumpCard;
  public $deckSize;
  public $roundNumber;
  public $moveType;
  public $tableDiscardedPairs;
  public $enemyCard;
  
  public function __construct()
  {

  }

}