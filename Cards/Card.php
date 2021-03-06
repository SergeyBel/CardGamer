<?php

class Card
{
 

  public $suit; // 1...4
  public $value;  // 6..14
  
  const CARD_SUIT_MIN = 1;
  const CARD_SUIT_MAX = 4;
  const CARD_VAL_MIN = 6;
  const CARD_VAL_MAX = 14;

  function __construct($suit, $value)
  {
    $this->suit = $suit;
    $this->value = $value;
  }

  function isCardCorrect()
  {
    return $this->suit >= $this::CARD_SUIT_MIN && $this->suit <= $this::CARD_SUIT_MAX &&
          $this->value >= $this::CARD_VAL_MIN && $this->value <= $this::CARD_VAL_MAX;
  }

  public function CanBeAttackCard($tableCards)
  {
    if (empty($tableCards))
      return true;
    foreach($tableCards as $tableCard)
    {
      if ($tableCard->value == $this->value)
        return true;
    }
    return false;
  }

  public function BeatAnotherCard($attackCard, $trump)
  {
    if ($this->suit == $trump->suit && $attackCard->suit != $trump->suit)
      return true;

    if ($this->suit == $attackCard->suit && $this->value > $attackCard->value)
      return true;


    return false;
  }


}