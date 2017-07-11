<?php


class GameHistory
{
  public $states;


  public function __construct()
  {
    $this->states = array();
  }

  public function addState($item)
  {
    $this->states[] = $item;
  }

  public function toString()
  {
    return json_encode($this->states);
  }

  public function saveToFile($file)
  {
    $text = $this->toString();
    file_put_contents($file, $text);
  }

  public function loadFromFile($file)
  {
    $text = file_get_contents($file);
    $this->states = json_decode($text, 1);
  }

}