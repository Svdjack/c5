<?php

$link = mysqli_connect('localhost', 'tvoyafirma', 'Gk`_-LbTLpRyp@5');
if (!$link) {
    die('CONNECT FAIL');
}

mysqli_set_charset($link, 'utf8');

function query($sql)
{
    global $link;
    $res = mysqli_query($link, $sql);
    if (!$res) {
        die(mysqli_error($link) . '<br/>' . $sql);
    }
    return $res;
}

function last_id()
{
    global $link;
    return mysqli_insert_id($link);
}

function copy_table($db, $new_db, $table, $columns, $condition = '1')
{
    $sql = '';
    foreach ($columns AS $name => $sinonym) {
        $sql .= $name . ' AS ' . $sinonym . ', ';
    }
    $sql = trim($sql, ', ');
    query("INSERT IGNORE INTO {$new_db}.{$table} SELECT {$sql} FROM {$db}.{$table} WHERE {$condition}");
}


function get_columns($base, $table)
{
    $result = [];
    $columns = query("SHOW COLUMNS FROM {$base}.{$table};");
    foreach ($columns AS $column) {
        $result[$column['Field']] = $column['Field'];
    }
    return $result;
}


function insert_array($table, $array)
{
    global $link;
    $fields = '';
    $values = '';
    foreach ($array AS $key => $value) {
        $value = mysqli_escape_string($link, $value);
        $fields .= "{$key}, ";
        $values .= $value ? "'$value', " : "NULL, ";
    }
    $fields = trim($fields, ', ');
    $values = trim($values, ', ');

    return query("INSERT INTO {$table} ($fields) VALUES ($values)");

}

function insert_multi_array($table, $array, $replace = '')
{
    if (empty($array)) return;
    global $link;

    $values = '';
    foreach ($array AS $data) {
        $fields = '';
        $sql = '';
        foreach ($data AS $key => $value) {
            $fields .= $key . ', ';
            $value = mysqli_escape_string($link, $value);
            $sql .= $value ? "'{$value}', " : "NULL, ";
        }
        $fields = trim($fields, ', ');
        $values .= '(' . trim($sql, ', ') . '), ';
    }
    $values = trim($values, ', ');
    $array && query("INSERT {$replace} INTO {$table} ($fields) VALUES $values");
}