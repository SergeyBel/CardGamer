<?php

class PlayerMove
{
  public $type; 
  public $card;


  public function __construct()
  {

  }

  public static function fromString(string $str) : PlayerMove {
    $move = new PlayerMove();
    return $move;
  }
}