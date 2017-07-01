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
  private $movingPlayer;

  const CARDS_IN_HAND = 6;

  const STATE_GAME_START = 1;
  const STATE_ROUND_START= 4;
  const STATE_ROUND_FINISH = 5;
  const STATE_GAME_FINISH = 10;

  public function __construct($player1, $player2)
  {
    $this->deck = new CardsDeck();
    $this->deck->shuffleCards();
    $this->tableCards = array();
    $this->player1 = $player1;
    $this->player2 = $player2;
    $this->state = $this::STATE_GAME_START;
    $this->movingPlayer = $player1;
  }

  public function distributeCards()
  {
    $this->distributeCardsForPlayer($this->player1);
    $this->distributeCardsForPlayer($this->player2);
  }

  private function distributeCardsForPlayer($player)
  {
    $cards = $player->getCards();
    $newCards = $this->deck->popCards($this::CARDS_IN_HAND - count($cards));
    $player->setCards(array_merge($cards, $newCards));
  }

  private function addCardsForPlayer($player, $newCards)
  {
    $cards = $player->getCards();
    $player->setCards(array_merge($cards, $newCards));
  }

  public function startRound()
  {
    $this->state = $this::STATE_ROUND_START;
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
    return $this->movingPlayer;
  }

  public function changeMovePlayer()
  {
    if ($this->movingPlayer == $this->player1)
      $this->movingPlayer = $this->player2;
    else
      $this->movingPlayer = $this->player1;
  }

  public function updateStateAfterPlayerMove($move)
  {
    switch($move->type)
    {
      //Todo: change to actual statuses
      case PlayerMove::DROP:
      case PlayerMove::TAKE:
      case PlayerMove::ATTACK:
      case PlayerMove::DEFENCE:
    }
  }

  private function updateStateAfterDropPlayerMove($move)
  {
    $this->tableCards = array();
    $this->state = $this::STATE_ROUND_FINISH;
    $this->changeMovePlayer();
  }

  private function updateStateAfterTakePlayerMove($move)
  {
    $this->tableCards = array();
    $this->state = $this::STATE_ROUND_FINISH;
    $this->addCardsForPlayer($this->movingPlayer, $tableCards);
    $this->changeMovePlayer();
  }
}