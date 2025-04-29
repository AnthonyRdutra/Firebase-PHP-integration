<?php

require dirname(__DIR__) . '\src\ConnectDB.php';
date_default_timezone_set('America/Sao_Paulo');

abstract class FireBaseAbstract
{
  private $db;

  private function connect()
  {
    $this->db = new ConnectDB();
    $this->db->connect();
  }

  public function list(string $table_name, $filter = null)
  {
    $this->connect();
    $response = $this->db->list($table_name, $filter);
    if ($response === null) {
      echo "\nNenhuma tabela encontrada no endereço especificado\n";
    } else {
      return $response;
    }
  }

  public function update(string $table_name, $array)
  {
    $this->connect();
    $this->db->update($table_name, $array);
  }

  public function add(string $table_name, $array)
  {
    $this->connect();
    $this->db->add($table_name, $array);
  }
}
