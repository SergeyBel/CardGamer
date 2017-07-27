<?php

require_once("Cards/Card.php");
require_once("Cards/CardsDeck.php");

require_once("Game/Game.php");
require_once("Game/GameState.php");
require_once("Game/Round.php");

require_once("History/GameHistory.php");
require_once("History/GameHistoryItem.php");

require_once("Player/PlayerData.php");
require_once("Player/PlayerExe.php");
require_once("Player/PlayerMove.php");
require_once("Player/Player.php");



class ClassFactory
{
  static public function getFactory() {
      return new ClassFactory();
  }

  public function get(...$args) {
    if(empty($args))
      throw new Exception("Class not specified");
    $className = $args[0];
    switch ($className) {
      case "player":
        $this->validateNumArgs($args, 2);
        return new Player($args[1], $args[2]);
      case "game":
        $this->validateNumArgs($args, 2);
        return new Game($args[1], $args[2]);
      case "history":
        $this->validateNumArgs($args, 0);
        return new GameHistory();

      default:
        throw new Exception("Class not accessible");
    }
  }

  private function validateNumArgs($args, $required_num) {
    if(count($args) != $required_num + 1)
      throw new Exception("Invalid number of arguments for class $args[0], $required_num required");
  }
}