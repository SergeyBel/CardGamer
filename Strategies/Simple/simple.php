<?php

define('STEP_ATTACK', 1);
define('STEP_DEFENCE', 2);


while (True)
{
  list($hand, $table, $trump, $type, $enemyCard) = ReadHand();

  if ($type == STEP_ATTACK)
    $ans = Attack($hand, $table);
  else if ($type == STEP_DEFENCE)
    $ans = Defend($hand, $trump, $table, $enemyCard);
  
  Write($ans->toText());
}


class Card
{
  public $suit;
  public $value;




  public function BeatAnotherCard($attackCard, $trump)
  {
    if ($this->suit == $trump && $attackCard != $trump)
      return true;

    if ($this->suit == $trump && $attackCard == $trump && $this->value > $attackCard->value)
      return true;

    if ($this->suit != $trump && $attackCard != $trump && $this->value > $attackCard->value)
      return true;

    return false;
  }

  function toText()
  {
    return $this->suit.",".$this->value."\n";
  }
}


class Answer
{
  public $type;
  public $card;

  const ANSWER_ATTACK = 1;
  const ANSWER_DEFEND = 2;
  const ANSWER_DISCARD = 3;
  const ANSWER_TAKE = 4;

  function toText()
  {
    $text = '';
    $text.= $this->type."\n";
    if ($this->card)
      $text.= $this->card->toText();
    return $text;
  }
}



function Read()
{
  return trim(fgets(STDIN));
}

function Write($text)
{
  fwrite(STDOUT, "$text");
}

function ReadCard()
{
  $line = Read();
  $a = explode(',', $line);
  $c = new Card();
  $c->suit = $a[0];
  $c->value = $a[1];
  return $c;
}


function ReadHand()
{
  $deckCount = Read();
  $trump = ReadCard();
  $handCount = Read();
  $hand = array();
  for($i = 0; $i < $handCount; $i++)
    $hand[] = ReadCard();

  $tableCount = Read();
  $table = array();
  for($i = 0; $i < $tableCount; $i++)
    $table[] = ReadCard();

  $type = Read();
  if ($type == STEP_DEFENCE)
    $enemyCard = ReadCard();
  else
    $enemyCard = null;

  return array($hand, $table, $trump, $type, $enemyCard);
}




function Attack($hand, $table)
{
  if (!empty($table))
  {

    $values = array();
    foreach ($table as $t)
      $values[] = $t->value;
    $values = array_unique($values);

    $answeredCard = null;
    foreach($hand as $h)
    {
      if (in_array($h->value, $values))
      {
        $answeredCard = $h;
        break;
      }
    }
  }
  else
  {
    $answeredCard = $hand[0];
  }

  $ans = new Answer();
  if ($answeredCard)
  {
    $ans->type = Answer::ANSWER_ATTACK;
    $ans->card = $answeredCard;
  }
  else
  {
    $ans->type = Answer::ANSWER_DISCARD;
  }

  return $ans;
}



function Defend($hand, $trump, $table, $enemy)
{
  $ans = new Answer();
  foreach($hand as $h)
  {
    if ($h->BeatAnotherCard($enemy, $trump))
    {
      $ans->type = Answer::ANSWER_DEFEND;
      $ans->card = $h;
      return $ans;
    }

    $ans->type = Answer::ANSWER_TAKE;
    return $ans;
  }

}


