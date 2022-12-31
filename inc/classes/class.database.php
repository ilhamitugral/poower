<?php

class Database {
    
    private $db;
    public $prefix;
    public $insert_id;
    private $error;
    
    function __construct(array $data) {

        foreach(['hostname', 'username', 'password', 'dbname', 'prefix'] as $d) {
            if(!array_key_exists($d, $data)) {
                $data[$d] = '';
            }
        }

        $this->prefix = $data['prefix'];

        $this->db = new mysqli($data['hostname'], $data['username'], $data['password'], $data['dbname']);
        if($this->db->connect_errno) {
            die(sprintf('Veritabanı Bağlantı Hatası: %s', $this->db->connect_error));
        }else {
            $this->db->query('SET NAMES utf8');
        }
    }

    public function query(string $q = '') {
        $query = $this->db->query($q);
        $this->error = $this->db->error;
        $this->insert_id = $this->db->insert_id;
        return $query;
    }

    public function commit() {
        $this->db->commit();
    }

    public function rollback() {
        $this->db->rollback();
    }
}

?>