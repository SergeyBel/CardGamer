<?php

class Card
{
 

  public $suit; // 1...3
  public $value;  // 6..14
  
  const CARD_SUIT_MIN = 1;
  const CARD_SUIT_MAX = 3;
  const CARD_VAL_MIN = 6;
  const CARD_VAL_MAX = 14;

  function __construct($suit, $value)
  {
    $this->suit = $suit;
    $this->value = $value;
  }

  function isCardCorrect()
  {
    return $this->suite >= $this::CARD_SUIT_MIN && $this->suit <= $this::CARD_SUIT_MAX &&
          $this->value >= $this::CARD_VAL_MIN && $this->value <= $this::CARD_VAL_MAX;
  }


}