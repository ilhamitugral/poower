<?php

class DatabaseUsers {

    private $p;

    function __construct(string $p) {
        $this->p = $p;
    }

    public function usersCount() {
        return sprintf('SELECT id FROM %susers', $this->p);
    }

    public function createNewUsers(array $p = []) {
        foreach(['fullname', 'username', 'password', 'email'] as $d) {
            if(!array_key_exists($d, $p)) {
                $p[$d] = '';
            }
        }

        return sprintf('INSERT INTO %susers SET
        added_date = \'%s\',
        authority = 1,
        fullname = \'%s\',
        username = \'%s\',
        password = \'%s\',
        email = \'%s\',
        status = 1',
        $this->p,
        date('Y-m-d H:i'),
        $p['fullname'],
        $p['username'],
        $p['password'],
        $p['email']);
    }

    public function createNewSession(array $p = []) {
        foreach(['ip', 'token', 'id', 'user_agent'] as $d) {
            if(!array_key_exists($d, $p)) {
                $p[$d] = '';
            }
        }

        return sprintf('INSERT INTO %1$ssessions SET
        added_date = \'%2$s\',
        last_update = \'%2$s\',
        ip = \'%3$s\',
        token = \'%4$s\',
        u_id = \'%5$d\',
        user_agent = \'%6$s\',
        status = 1',
        $this->p,
        date('Y-m-d H:i:s'),
        $p['ip'],
        $p['token'],
        $p['id'],
        $p['user_agent']);
    }

    public function login(string $u, string $p) {
        return sprintf('SELECT
        id,
        username,
        password
        FROM %susers
        WHERE username = \'%s\' &&
        password = \'%s\'',
        $this->p,
        $u, $p);
    }

    public function loginWithToken(string $t) {
        return sprintf('SELECT
        %1$susers.id,
        %1$susers.added_date,
        %1$susers.authority,
        %1$susers.fullname,
        %1$susers.username,
        %1$susers.email,
        %1$susers.status,
        %1$ssessions.added_date,
        %1$ssessions.last_update,
        %1$ssessions.ip,
        %1$ssessions.token,
        %1$ssessions.u_id,
        %1$ssessions.user_agent,
        %1$ssessions.status
        FROM %1$susers INNER JOIN %1$ssessions
        ON %1$susers.id = %1$ssessions.u_id
        WHERE %1$ssessions.token = \'%2$s\' &&
        %1$ssessions.status = 1',
        $this->p, $t);
    }

    public function update(string $t) {
        return sprintf('UPDATE %ssessions SET
        last_update = \'%s\'
        WHERE token = \'%s\'',
        $this->p,
        date('Y-m-d H:i:s'),
        $t);
    }

    public function logout(string $t = '') {
        return sprintf('UPDATE %ssessions SET
        status = 0
        WHERE token = \'%s\'',
        $this->p, $t);
    } 
}

?>