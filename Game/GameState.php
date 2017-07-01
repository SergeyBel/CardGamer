<?php
require_once('Cards\CardsDeck.php');


class GameState
{
  public $trump;
  public $tableCards;
  public $deck;
  private $player1;
  private $player2;
  private $state;
  private $gameHistory;

  const CARDS_IN_HAND = 6;

  const STATE_GAME_START = 1;
  const STATE_ROUND_FINISH = 5;
  const STATE_GAME_FINISH = 10;

  public function __construct($player1, $player2)
  {
    $this->deck = new CardsDeck();
    $this->deck->shuffleCards();

    $this->tableCards = new CardsArray();
    $this->player1 = $player1;
    $this->player2 = $player2;
    $player1->setCards($this->deck->popCards(CARDS_IN_HAND));
    $player2->setCards($this->deck->popCards(CARDS_IN_HAND));
    $this->state = STATE_GAME_START;
  }

  public function getState()
  {
    return $this->state;
  }

  public function getHistory()
  {
    return $this->gameHistory;
  }

  public function getMovePlayer()
  {

  }

  public function updateStateAfterPlayerMove($move)
  {

  }



}