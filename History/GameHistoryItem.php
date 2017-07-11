<?php

class GameHistoryItem
{
  public $deckCount;
  public $player1Cards;
  public $player2Cards;
  public $tableCards;
  public $trump;


  public function __construct($player1Cards, $player2Cards, $tableCards, $deckCount, $trump)
  {
    $this->player1Cards = $player1Cards;
    $this->player2Cards = $player2Cards;
    $this->tableCards = $tableCards;
    $this->deckCount = $deckCount;
    $this->trump = $trump;
  }
}