<?php
require_once('Cards\Card.php');


class CardsDeck
{
  private $cards;

  public function __construct()
  {
    $cards = array();
    for ($suit = Card::CARD_SUIT_MIN; $suit <= Card::CARD_SUIT_MAX; $suit++)
    {
      for ($value = Card::CARD_VAL_MIN; $value <= Card::CARD_VAL_MAX; $value++)
      {
        $card = new Card($suit, $value);
        $cards.push($card);
      }
    }
  }

  public function shuffleCards()
  {
    $N = 100;
    for ($i = 0; $i < $N; $i++)
    {
      $j = rand(0, count($cards));
      $k = rand(0, count($cards));
      $c = $cards[$k];
      $cards[$k] = $cards[$j];
      $cards[$j] = $c;
    }
  }

}
