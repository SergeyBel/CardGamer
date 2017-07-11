<?php


class GameHistory
{
  public $states;
  public $next;


  public function __construct()
  {
    $this->states = array();
    $this->next = 0;
  }

  public function addState($item)
  {
    $this->states[] = $item;
  }

  public function getNextState()
  {
    if ($this->next == count($this->states))
    {
      $this->next = 0;
      return false;
    }
    return $this->states[$this->next++];
  }

  public function toString()
  {
    return serialize($this);
  }

  public function saveToFile($file)
  {
    $text = $this->toString();
    file_put_contents($file, $text);
  }

  public function loadFromFile($file)
  {
    $text = file_get_contents($file);
    $gh =  unserialize($text);
    $this->states = $gh->states;
    $this->next = $gh->next;
  }

}