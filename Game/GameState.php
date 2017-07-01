<?php
require_once('Cards\CardsDeck.php');


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

  const CARDS_IN_HAND = 6;

  const STATE_GAME_START = 1;
  const STATE_ROUND_START= 4;
  const STATE_ROUND_FINISH = 5;
  const STATE_GAME_FINISH = 10;

  public function __construct($player1, $player2)
  {
    $this->deck = new CardsDeck();
    $this->deck->shuffleCards();
    $this->trump = $this->deck->getRandomCard();
    $this->tableCards = array();
    $this->state = $this::STATE_GAME_START;
    $this->player1 = $player1;
    $this->player2 = $player2;
    $this->movingPlayer = $player1;
    $this->movingType = PlayerData::TYPE_ATTACK;
  }

  public function createPlayerData()
  {
    $data = new PlayerData();
    $data->trumpCard = $this->trump;
    $data->deckSize = $this->deck->getSize();
    $data->moveType = $this->movingType;
    $data->tableDiscardedPairs = $table;
    if ($this->movingType == PlayerData::TYPE_DEFENCE)
      $data->enemyCard = $this->lastAttackCard;
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
        $this->updateStateAfterAttacklayerMove($move);
        break;
      case PlayerMove::TYPE_DEFENCE:
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
    $this->playerState->changeMovePlayer();
  }

  private function updateStateAfterTakePlayerMove($move)
  {
    $this->tableCards = array();
    $this->state = $this::STATE_ROUND_FINISH;
    $this->addCardsForPlayer($this->movingPlayer, $tableCards);
    $this->playerState->changeMovePlayer();
  }

  private function updateStateAfterAttackPlayerMove($move)
  {
    if (!$move->card->CanBeAttackCard())
    {
      $this->state = STATE_GAME_FINISH;
      return;
    }
    $this->tableCards[] = $move->card;
    $this->lastAttackCard = $move->card;
    $this->movingType = PlayerData::TYPE_DEFENCE;
    $this->playerState->changeMovePlayer();
  }

    private function updateStateAfterDefencePlayerMove($move)
  {
    if (!$move->card->BeatAnotherCard($this->lastAttackCard, $this->trump))
    {
      $this->state = STATE_GAME_FINISH;
      return;
    }
    $this->tableCards[] = $move->card;
    $this->movingType = PlayerData::TYPE_ATTACK;
    $this->playerState->changeMovePlayer();
  }


  public function distributeCards()
  {
    $this->distributeCardsForPlayer($this->player1);
    $this->distributeCardsForPlayer($this->player2);
  }

  private function distributeCardsForPlayer($player)
  {
    $newCards = $this->deck->popCards($this::CARDS_IN_HAND - $player->coundHandCards());
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

  public function getMovePlayer()
  {
    if ($this->movingPlayer == $this->player1)
      $this->movingPlayer = $this->player2;
    else
      $this->movingPlayer = $this->player1;
  }
}