<?php
require 'D:/github/PostoDsan/vendor/autoload.php';

use Kreait\Firebase\Factory;

class ConnectDB
{
    private $firebase;

    public function connect()
    {
        print(__METHOD__ . "\n");
        $serviceAccountKeyFilePath = 'YOUR PATH TO THE JSON KEY';

        try {
            // Creates a Firebase instance using the service account key file
            $this->firebase = (new Factory)
                ->withServiceAccount($serviceAccountKeyFilePath)
                ->withDatabaseUri('https://YOUR-RTDABASE.firebaseio.com')
                ->createDatabase();
            echo "Connected to the database\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
                echo "\nContent successfully added";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            try {
                $ref->push($array);
                echo "\nContent successfully added";
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
                echo "\nTable $table_name successfully created";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            echo "\nClient already exists.\n";
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
                echo "\nTable $table_name successfully updated";
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("\nRequested table does not exist\n");
        }
    }

    public function list(string $table_name, $filter = null)
    {
        print(__METHOD__ . "\n");
        try {
            $ref = $this->firebase->getReference($table_name);
            $data = $ref->getValue();

            if (empty($data)) {
                return null;
            }

            // If no filter is provided, return all data
            if (!$filter) {
                return $data;
            }

            $result = [];

            // If the filter is an ID (primary key), return it directly if it exists
            if (isset($data[$filter])) {
                return [$filter => $data[$filter]];
            }

            // If the filter is a value inside the records
            foreach ($data as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $field => $value) {
                        if (is_string($value) && stripos($value, $filter) !== false) {
                            $result[$key] = $item;
                            break;
                        }
                    }
                }
            }

            if (empty($result)) {
                throw new Exception("\nNo results found for the provided filter.\n");
            }

            return $result;
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
