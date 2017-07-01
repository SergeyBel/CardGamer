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
        $this->cards[] = $card;
      }
    }
  }

  public function shuffleCards()
  {
    $N = 100;
    for ($i = 0; $i < $N; $i++)
    {
      $j = rand(0, count($this->cards) - 1);
      $k = rand(0, count($this->cards) - 1);
      $this->swapCards($k, $j);
    }
  }

  public function popCards($count)
  {
    if ($count <= 0)
      return;
    return array_slice($this->cards, 0, $count);
  }

  public function getTrump()
  {
    $j = rand(0, count($this->cards) - 1);
    $this->swapCards($j, count($this->cards) - 1);
    return $this->cards[count($this->cards) - 1];
  }

  private function swapCards($i, $j)
  {
    $c = $this->cards[$i];
    $this->cards[$i] = $this->cards[$j];
    $this->cards[$j] = $c;

  }

  public function getSize()
  {
    return count($this->cards);
  }

}
