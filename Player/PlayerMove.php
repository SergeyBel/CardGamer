<?php

// data that player return 
class PlayerMove
{
  const TYPE_ATTACK = 1;
  const TYPE_DEFEND = 2;
  const TYPE_DISCARD = 3;
  const TYPE_TAKE = 4;

  const TYPES = [PlayerMove::TYPE_ATTACK, PlayerMove::TYPE_DEFEND, PlayerMove::TYPE_DISCARD, PlayerMove::TYPE_TAKE];

  const ATTACK_TYPES = [PlayerMove::TYPE_ATTACK, PlayerMove::TYPE_DISCARD];
  const DEFENCE_TYPES = [PlayerMove::TYPE_DEFEND, PlayerMove::TYPE_TAKE];

  const TYPE_NEED_CARD = [PlayerMove::TYPE_ATTACK, PlayerMove::TYPE_DEFEND];


  public $type; // move action
  public $card; // if action needs card, card save here


  public function __construct($type, Card $card)
  {
    $this->card = $card;
    $this->type = $type;
  }

}