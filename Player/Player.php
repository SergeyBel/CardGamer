<?php


class Player
{
  private $exe;
  private $hand;
  public $id;

  public function __construct($id, $filePath)
  {
    $this->id = $id;
    $this->hand = array();
    $this->exe = new PlayerExe($filePath);
  }

  public function makeMove(PlayerData $playerData): PlayerMove
  {
    $stringData = $this->playerDataToString($playerData);
    $move = $this->interactWithPlayer($stringData);
    $this->validateMoveAndUpdateHand($playerData, $move);
    return $move;
  }

  protected function validateMoveAndUpdateHand(PlayerData $playerData, PlayerMove $move) {
    if($playerData->moveType == PlayerData::TYPE_ATTACK)
      if(!in_array($move->type, PlayerMove::ATTACK_TYPES))
        throw new Exception("Invalid reply move type: $move->type in response to $playerData->moveType");

    if($playerData->moveType == PlayerData::TYPE_DEFEND)
      if(!in_array($move->type, PlayerMove::DEFENCE_TYPES))
        throw new Exception("Invalid reply move type: $move->type in response to $playerData->moveType");

    if($move->card)
    {
      $id = array_search($move->card, $this->hand);
      if ($id === false)
        throw new Exception("Replied with card not in hand");

      array_splice($this->hand, $id, 1);
    }
  }

  protected function interactWithPlayer(string $str): PlayerMove
  {
    $this->exe->sendString($str);

    $stringMoveType = $this->exe->readString();

    if(empty($stringMoveType) or !is_numeric($stringMoveType))
      throw new Exception("Move type not numeric: $stringMoveType");

    $moveType = intval($stringMoveType);

    if(!in_array($moveType, PlayerMove::TYPES))
      throw new Exception("Unknown move type: $moveType");

    if(in_array($moveType, PlayerMove::TYPE_NEED_CARD)) {
      $stringCard = $this->exe->readString();
      $card = $this->cardFromString($stringCard);
    } else
      $card = null;

    return new PlayerMove($moveType, $card);
  }

  protected function playerDataToString(PlayerData $playerData) : string {
    $res = "";
    $res .= $playerData->deckSize."\n";
    $res .= $this->cardToString($playerData->trumpCard)."\n";
    $res .= count($this->hand)."\n";
    foreach ($this->hand as $card) {
      $res .= $this->cardToString($card) . "\n";
    }
    $res .= count($playerData->tableDiscardedCards)."\n";
    foreach ($playerData->tableDiscardedCards as $card) {
      $res .= $this->cardToString($card) . "\n";
    }
    $res .= $playerData->moveType."\n";
    if($playerData->enemyCard)
      $res .= $this->cardToString($playerData->enemyCard) . "\n";

    return $res;
  }

  public function addHandCard(Card $card)
  {
    $this->hand[] = $card;
  }

  public function addHandCards(array $cards)
  {
    foreach ($cards as $card) {
      $this->addHandCard($card);
    }
  }

  public function getHandCards() : array
  {
    return $this->hand;
  }

  public function countHandCards() : int
  {
    return count($this->hand);
  }

  protected function cardToString(Card $card) : string {
    return $card->suit.",".$card->value;
  }

  protected function cardFromString(string $str) : Card {
    $data = explode(",", $str);

    if(!isset($data[1]) or isset($data[2]) or !is_numeric($data[0]) or !is_numeric($data[1]) )
      throw  new Exception("Invalid card syntax: $str");

    $card = new Card($data[0], $data[1]);
    if(!$card->isCardCorrect())
      throw new Exception("Card is not correct: $str");

    return $card;
  }
}