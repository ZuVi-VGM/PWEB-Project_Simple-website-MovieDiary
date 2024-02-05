<?php
require_once __DIR__ . "/../config.php";

class dbManager
{
    private $connection;
    private $conn_status = false;

    public function open(){

        if($this->conn_status)
            $this->close();

        global $dbHostname;
        global $dbUsername;
        global $dbPassword;
        global $dbName;

        $this->connection = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);
        if ($this->connection->connect_errno) {
            echo 'Connect Error (' . $this->connection->connect_errno . ') ' . $this->connection->connect_error;
            exit();
        }

        $this->conn_status = true;

    //TODO: remove debugging msg
        //echo "connection opened<br>";
    }

    public function query($queryText) {
        if(!$this->conn_status)
            $this->open();

        $result = $this->connection->query($queryText);
        if (!$result) {
            printf("Error message: %s\n", $this->connection->error);
            exit();
        }

        return $result;
    }

    public function sqlInjectionFilter($parameter){
        if(!$this->conn_status)
            $this->open();

        if(is_array($parameter))
        {
            foreach($parameter as &$param)
            {
                $param = $this->connection->real_escape_string($param);
            }
            unset($param);

            return $parameter;
        } else {
            return $this->connection->real_escape_string($parameter);
        }
    }

    public function close(){
        if($this->conn_status)
        {
            $this->connection->close();
            $this->connection = null;
            $this->conn_status = false;
        }

        //TODO: remove debugging msg
        //echo "Connection closed<br>";
    }

}