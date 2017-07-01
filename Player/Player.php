<?php


class Player
{
  private $filePath;
  private $hand;

  public function __construct($filePath)
  {
    $hand = array();
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

  protected function playerDataToString(PlayerData $playerData) : string {
    $res = "";
    $res .= $playerData->roundNumber."\n";
    $res .= $playerData->deckSize."\n";
    $res .= $this->cardToString($playerData->trumpCard)."\n";
    $res .= count($this->hand)."\n";
    foreach ($this->hand as $card) {
      $res .= $this->cardToString($card) . "\n";
    }
    $res .= $playerData->moveType."\n";
    $res .= count($playerData->tableDiscardedPairs)."\n";
    foreach ($playerData->tableDiscardedPairs as $pair) {
      $res .= $this->cardToString($pair[0]) . "\n";
      $res .= $this->cardToString($pair[1]) . "\n";
    }
    if($playerData->enemyCard)
      $res .= $this->cardToString($playerData->enemyCard) . "\n";

    return $res;
  }

  public function playerMoveFromString(string $str) : PlayerMove {
    $move = new PlayerMove();
    return $move;
  }

  public function setCards($cards)
  {
    $this->hand = $cards;
  }

  function getCards()
  {
    return $this->hand;
  }

  protected function cardToString(Card $card) : string {
    return $card->suit.",".$card->value;
  }

  protected function cardFromString(string $str) : Card {
    $card = new Card();
    $data = explode(",", $str);
    //TODO: Check data for valid input
    $card->suit = $data[0];
    $card->value = $data[1];
    return $card;
  }
}