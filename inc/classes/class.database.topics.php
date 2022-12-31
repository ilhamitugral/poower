<?php

class DatabaseTopics {

    private $p;

    function __construct(string $p = '') {
        $this->p = $p;
    }

    public function checkTitle(string $t = '') {
        return sprintf('SELECT title FROM %stopics WHERE title = \'%s\'', $this->p, $t);
    }

    public function createNewTopic(array $p = []) {
        return sprintf('INSERT INTO %1$stopics SET
        added_date = \'%2$s\',
        last_update = \'%2$s\',
        url = \'%3$s\',
        parent = %4$d,
        category = %5$d,
        author = %6$d,
        title = \'%7$s\',
        keywords = \'%8$s\',
        status = %9$d',
        $this->p,
        date('Y-m-d H:i:s'),
        $p['url'],
        $p['parent'],
        $p['category'],
        $p['author'],
        $p['title'],
        $p['keywords'],
        $p['status']);
    }

    public function displayTopic(string $u) {
        return sprintf('SELECT
        %1$stopics.added_date,
        %1$stopics.last_update,
        %1$stopics.url,
        %1$stopics.parent,
        %1$stopics.category,
        %1$stopics.author,
        %1$stopics.title,
        %1$stopics.keywords,
        %1$stopics.status,
        %1$susers.id,
        %1$susers.fullname,
        %1$susers.username
        FROM %1$stopics INNER JOIN %1$susers ON
        %1$stopics.author = %1$susers.id
        WHERE %1$stopics.url = \'%2$s\' &&
        %1$stopics.status = 1',
        $this->p, $u);
    }
}

?>