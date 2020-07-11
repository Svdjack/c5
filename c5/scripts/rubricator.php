<?php

require_once "db.php";

query('use tvoyafirma_new');

$groups = query('SELECT * FROM groups');
print "id|parent_id|name|original|level|count" . PHP_EOL;

foreach ($groups as $group) {
    $count = query("SELECT count(firm_id) AS count FROM firm_group WHERE group_id = {$group['id']}")->fetch_assoc();
    print "'{$group['id']}'|'{$group['parent']}'|'{$group['name']}'|'{$group['original']}'|'{$group['level']}'|'{$count['count']}'" . PHP_EOL;
}