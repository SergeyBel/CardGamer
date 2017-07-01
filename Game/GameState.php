<?php

class GameState
{
  public $trump;
  public $tableCards;
  public $deck;
  private $state;
  private $gameHistory;
  private $player1;
  private $player2;
  private $movingPlayer;
  private $movingType;
  private $lastAttackCard;
  private $winner;

  const CARDS_IN_HAND = 6;

  const STATE_GAME_START = 1;
  const STATE_ROUND_START= 4;
  const STATE_ROUND_FINISH = 5;
  const STATE_GAME_FINISH = 10;

  public function __construct(Player $player1, Player $player2)
  {
    $this->deck = new CardsDeck();
    $this->deck->shuffleCards();
    $this->trump = $this->deck->getTrump();
    $this->tableCards = array();
    $this->state = $this::STATE_GAME_START;
    $this->player1 = $player1;
    $this->player2 = $player2;
    $this->movingPlayer = $player1;
    $this->movingType = PlayerData::TYPE_ATTACK;
    $winner = null;
  }

  public function createPlayerData()
  {
    $data = new PlayerData();
    $data->trumpCard = $this->trump;
    $data->deckSize = $this->deck->getSize();
    $data->moveType = $this->movingType;
    $data->tableDiscardedCards = $this->tableCards;
    if ($this->movingType == PlayerData::TYPE_DEFEND)
      $data->enemyCard = $this->lastAttackCard;
    else
      $data->enemyCard = false;
    return $data;
  }

  public function updateStateAfterPlayerMove($move)
  {
    switch($move->type)
    {
      case PlayerMove::TYPE_DISCARD:
        $this->updateStateAfterDiscardPlayerMove($move);
        break;
      case PlayerMove::TYPE_TAKE:
        $this->updateStateAfterTakePlayerMove($move);
        break;
      case PlayerMove::TYPE_ATTACK:
        $this->updateStateAfterAttackPlayerMove($move);
        break;
      case PlayerMove::TYPE_DEFEND:
        $this->updateStateAfterDefencePlayerMove($move);
        break;
      default:
        break;
    }
  }

  private function updateStateAfterDiscardPlayerMove($move)
  {
    $this->tableCards = array();
    $this->state = $this::STATE_ROUND_FINISH;
    $this->changeMovePlayer();
  }

  private function updateStateAfterTakePlayerMove($move)
  {
    $this->tableCards = array();
    $this->state = $this::STATE_ROUND_FINISH;
    $this->movingPlayer->addHandCards($this->tableCards);
    $this->changeMovePlayer();
  }

  private function updateStateAfterAttackPlayerMove($move)
  {
    if (!$move->card->CanBeAttackCard())
    {
      $this->state = GameState::STATE_GAME_FINISH;
      return;
    }
    if ($this->isGameFinished())
      return;
    $this->tableCards[] = $move->card;
    $this->lastAttackCard = $move->card;
    $this->movingType = PlayerData::TYPE_DEFEND;
    $this->changeMovePlayer();
  }

    private function updateStateAfterDefencePlayerMove($move)
  {
    if (!$move->card->BeatAnotherCard($this->lastAttackCard, $this->trump))
    {
      $this->state = GameState::STATE_GAME_FINISH;
      return;
    }
    if ($this->isGameFinished())
      return;
    $this->tableCards[] = $move->card;
    $this->movingType = PlayerData::TYPE_ATTACK;
    $this->changeMovePlayer();
  }

  private function isGameFinished()
  {
    if ($this->deck->getSize() == 0)
    {
      if ($this->player1->countHandCards() == 0)
      {
        $this->winner = $this->player1;
        $this->state = $this::STATE_ROUND_FINISH;
        return true;
      }

      if ($this->player2->countHandCards() == 0)
      {
        $this->winner = $this->player2;
        $this->state = $this::STATE_ROUND_FINISH;
        return true;    
      }
    }
    return false;
  }


  public function distributeCards()
  {
    $this->distributeCardsForPlayer($this->player1);
    $this->distributeCardsForPlayer($this->player2);
  }

  private function distributeCardsForPlayer(Player $player)
  {
    $newCards = $this->deck->popCards($this::CARDS_IN_HAND - $player->countHandCards());
    $player->addHandCards($newCards);
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

  public function getWinner()
  {
    return $this->winner;
  }

  public function changeMovePlayer() {
    if ($this->movingPlayer === $this->player1)
      $this->movingPlayer = $this->player2;
    else
      $this->movingPlayer = $this->player1;
  }

  public function getMovePlayer()
  {
    return $this->movingPlayer;
  }
}