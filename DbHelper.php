<?php
class DbHelper {
    private $db_resource;
    private $last_error = null;

    public function __construct($login, $password, $host, $db) {
        $this->db_resource = mysqli_connect($host, $login, $password, $db);

        if (!$this->db_resource) {
            $this->last_error = mysqli_connect_error();
        }
    }

    public function executeQuery($sql) {
        $res = mysqli_query($this->db_resource, $sql);
        return $res;
    }

    public function getLastError() {
        return $this->last_error;
    }

    public function getLastId() {
        return mysqli_insert_id($this->db_resource);
    }
}

?>