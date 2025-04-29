<?php
require 'D:/github/PostoDsan/vendor/autoload.php';

use Kreait\Firebase\Factory;


class ConnectDB
{
    private $firebase;

    public function connect()
    {
        print(__METHOD__ . "\n");
        $serviceAccountKeyFilePath = dirname(__DIR__, 3) . '\json\db-postodsan-2d8fedaf278d.json';

        try {
            // Cria uma instância do Firebase passando o arquivo de chave
            $this->firebase = (new Factory)
                ->withServiceAccount($serviceAccountKeyFilePath)
                ->withDatabaseUri('https://db-postodsan-default-rtdb.firebaseio.com')
                ->createDatabase();
            echo "conectado ao banco de dados\n";
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }


    public function add(string $table_name, $array)
    {
        print(__METHOD__ . "\n");

        $ref = $this->firebase->getReference($table_name);
        $snapshot = $ref->getSnapshot();

        if (!$snapshot->exists()) {
            try {
                $ref->push($array);
                echo "\nConteúdo Adicionado com sucesso";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            try {
                $ref->push($array);
                echo "\nConteúdo Adicionado com sucesso";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }


    public function addNewClient(string $table_name, $array)
    {
        print(__METHOD__ . "\n");

        $ref = $this->firebase->getReference($table_name);
        $snapshot = $ref->getSnapshot();

        if (!$snapshot->exists()) {
            try {
                $ref->set($array);
                echo "\nTabela $table_name criada com sucesso";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            echo "\nCliente já existe.\n";
        }
    }


    public function update(string $table_name, $array)
    {
        print(__METHOD__ . "\n");

        $ref = $this->firebase->getReference($table_name);
        $snapshot = $ref->getSnapshot();

        if ($snapshot->exists()) {
            try {
                $ref->set($array);
                echo "\nTabela $table_name modificada com sucesso";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("\nTabela solicitada não existe\n");
        }
    }


    public function list(string $table_name, $filtro = null)
    {
        print(__METHOD__ . "\n");
        try {
            $ref = $this->firebase->getReference($table_name);
            $dados = $ref->getValue();

            if (empty($dados)) {
                return null;
            }

            // Se nenhum filtro for passado, retorna todos os dados
            if (!$filtro) {
                return $dados;
            }

            $resultado = [];

            // Se o filtro for um ID (chave principal), retorna diretamente se existir
            if (isset($dados[$filtro])) {
                return [$filtro => $dados[$filtro]];
            }

            // Se o filtro for um valor dentro dos registros
            foreach ($dados as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $campo => $valor) {
                        if (is_string($valor) && stripos($valor, $filtro) !== false) {
                            $resultado[$key] = $item;
                            break;
                        }
                    }
                }
            }

            if (empty($resultado)) {
                throw new Exception("\nNenhum resultado encontrado para o filtro informado.\n");
            }

            return $resultado;
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
