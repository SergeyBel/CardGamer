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



$gameHistory = new GameHistory();
$gameHistory->loadFromFile('history.txt');
$state = $gameHistory->getNextState();
$gameHistory->saveToFile('history.txt');

DrawState($state);

function DrawCard($arr, $i)
{
  $card = array_key_exists($i, $arr)?$arr[$i]:null;
  if ($card !== null)
  {
    $value = intval($card->value);
    $suit = intval($card->suit);
    echo '<td><img src="png/'.$value.'-'.$suit.'.png"></td>';
  }
  else
    echo '<td>&nbsp;</td>';
}

function DrawCardLine($arr, $cardsInLine, $offset)
{
  for ($i = $offset * $cardsInLine; $i < ($offset + 1) * $cardsInLine; $i++)
  {
    DrawCard($arr, $i);
  }
}


function DrawState($state)
{
  if (!$state)
    die("END");


?>

<?php header("Content-Type: text/html; charset=utf-8"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td { width: 7%; height: 25%; }
td img { display:block; width:100%; height:100%; }
</style>
<title>Поле</title>
</head>
<body>
<table  style="height:100%;width:100%; position: absolute; top: 0; bottom: 0; left: 0; right: 0;" border="1" >
<tbody>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><?php DrawCard(array($state->trump), 0); ?></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>


</tr>
<tr>
<?php DrawCardLine($state->player1Cards, 3, 0) ?>
<td>&nbsp;</td>
<?php DrawCardLine($state->tableCards, 6, 0) ?>
<td>&nbsp;</td>
<?php DrawCardLine($state->player2Cards, 3, 0) ?>



</tr>
<tr>
<?php DrawCardLine($state->player1Cards, 3, 1) ?>
<td>&nbsp;</td>
<?php DrawCardLine($state->tableCards, 6, 1) ?>
<td>&nbsp;</td>
<?php DrawCardLine($state->player2Cards, 3, 1) ?>


</tr>
<tr>
<?php DrawCardLine($state->player1Cards, 3, 2) ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php DrawCardLine($state->player2Cards, 3, 2) ?>


</tr>

</tbody>
</table>
</body>
</html>

<?php } ?>