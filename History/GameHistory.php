<?php


class GameHistory
{
  public $states;
  public $winner;


  public function __construct()
  {
    $this->winner = -1;
    $this->states = array();
  }

}