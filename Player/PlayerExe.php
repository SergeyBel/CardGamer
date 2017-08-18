<?php

class PlayerExe
{
  protected $process;
  protected $pipes;
  protected $log;

  public function __construct(string $commandLine)
  {
    $descriptorSpec = array(
      0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
      1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать
      2 => array("pipe", "w") // stderr - файл для записи
    );

    $this->process = proc_open($commandLine, $descriptorSpec, $this->pipes);

    if(!is_resource($this->process))
      throw new Exception("Process $commandLine did not start");

    $this->log = "";
  }

  public function __destruct()
  {
    fclose($this->pipes[0]);
    fclose($this->pipes[1]);
    proc_close($this->process);
  }

  public function sendString(string $str)
  {
    $this->log .= ">>>>>>>>>>>>\n$str";
    if(fwrite($this->pipes[0], $str) != strlen($str))
      throw new Exception("fwrite failed to write $str");
  }

  public function readString() : string
  {
    $res = trim(fgets($this->pipes[1]));
    if($res === false)
      throw new Exception("fgets failed");
    $this->log .= "<<<<<<<<<<<<\n$res\n";
    return $res;
  }

  public function getLog(): string
  {
    return $this->log;
  }
}