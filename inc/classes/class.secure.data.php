<?php

class SecureData {
    public function post(string $v, array $o = []) {
        return $this->check($_POST[$v], $o);
    }

    public function get(string $v, array $o = []) {
        return $this->check($_GET[$v], $o);
    }

    private function check(string $v, array $o = []) {
        $value = array_key_exists('htmlspecialchars', $o) ? htmlspecialchars($v) : $v;
        $value = array_key_exists('strip_tags', $o) ? strip_tags($value) : $value;
        $value = array_key_exists('addslashes', $o) ? addslashes($value) : $value;
        $value = array_key_exists('trim', $o) ? trim($value) : $value;

        return $value;
    }

    public function encrypt(string $v = '') {
        return empty($v) ? md5($v.time().rand(1, 999999)) : md5($v);
    }
}

?>