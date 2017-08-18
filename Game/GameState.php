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
  private $countAttackedCards;
  private $winner;

  const CARDS_IN_HAND = 6;

  const STATE_GAME_START = 1;
  const STATE_ROUND_START= 4;
  const STATE_ROUND_FINISH = 5;
  const STATE_GAME_FINISH = 10;

  public function __construct(Player $player1, Player $player2)
  {
    $this->gameHistory = new GameHistory();
    $this->deck = new CardsDeck();
    $this->deck->shuffleCards();
    $this->tableCards = array();
    $this->countAttackedCards  = 0;
    $this->state = $this::STATE_GAME_START;
    $this->player1 = $player1;
    $this->player2 = $player2;
    $this->distributeCards();
    $this->trump = $this->deck->getTrump();
    $this->setFirstMovingPlayer();
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
    
    $this->state = $this::STATE_ROUND_FINISH;
    $this->movingType = PlayerData::TYPE_ATTACK;
    $this->tableCards = array();
    $this->countAttackedCards = 0;
    $this->changeMovePlayer();
   
  }

  private function updateStateAfterTakePlayerMove($move)
  {
    $this->state = $this::STATE_ROUND_FINISH;
    $this->movingType = PlayerData::TYPE_ATTACK;
    $this->movingPlayer->addHandCards($this->tableCards);
    $this->tableCards = array();
    $this->countAttackedCards = 0;
    $this->changeMovePlayer();

  }

  private function updateStateAfterAttackPlayerMove($move)
  {
    if (!$move->card->CanBeAttackCard($this->tableCards))
    {
      $this->state = GameState::STATE_GAME_FINISH;
      return;
    }
    $this->countAttackedCards++;
    if ($this->countAttackedCards > GameState::CARDS_IN_HAND)
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
        $this->setWinner($this->player1, "Hand is free");
        return true;
      }

      if ($this->player2->countHandCards() == 0)
      {
        $this->setWinner($this->player2, "Hand is free");
        return true;    
      }
    }
    return false;
  }

  public function setWinner($player, $reason)
  {
    $this->winner = $player;
    $this->winReason = $reason;
    $this->state = $this::STATE_GAME_FINISH;
  }


  public function distributeCards()
  {
    if ($this->movingPlayer == $this->player1)
    {
      $firstToTake = $this->player1;
      $secondToTake = $this->player2;
    }
    else
    {
      $firstToTake = $this->player2;
      $secondToTake = $this->player1;
    }

    do {
      $c1 = $this->distributeCardForPlayer($firstToTake);
      $c2 = $this->distributeCardForPlayer($secondToTake);
    } while($c1 or $c2);

  }

  private function distributeCardForPlayer(Player $player)
  {
    if($player->countHandCards() >= $this::CARDS_IN_HAND)
      return 0;
    $newCards = $this->deck->popCards(1);
    if(!empty($newCards))
      $player->addHandCards($newCards);
    return count($newCards);
  }

  private function setFirstMovingPlayer()
  {
    $minTrump1 = $this->selectMinTrump($this->player1->getHandCards());
    $minTrump2 = $this->selectMinTrump($this->player2->getHandCards());
    $value1 = ($minTrump1 != null)?$minTrump1->value:0;
    $value2 = ($minTrump2 != null)?$minTrump2->value:0;
    if ($value2 > $value1)
      $this->movingPlayer = $this->player2;
    else
      $this->movingPlayer = $this->player1;
  }

  private function selectMinTrump($cards)
  {
    $minTrump = null;
    foreach($cards as $card)
    {
      if ($card->suit == $this->trump->suit && ($minTrump == null || $card->value < $minTrump->value))
        $minTrump = $card;
    }
    return $minTrump;
  }

  public function saveToHistory()
  {
    $item = new GameHistoryItem($this->player1->getHandCards(), $this->player2->getHandCards(), $this->tableCards, $this->deck->getSize(), $this->trump);
    $this->gameHistory->addState($item);
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

  public function getWinReason()
  {
    return $this->winReason;
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