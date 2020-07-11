<?php

require_once "db.php";

query("USE tvoyafirma_new");

$aliases = file_get_contents('aliases_kotel.csv');
$aliases = fopen("aliases_kotel.csv", "r+");
$result = [];
while ($aliase = stream_get_line($aliases, 1024 * 1024, "\n")) {
    $aliase = explode('|', $aliase);
    $group = query("SELECT * FROM groups WHERE original = '{$aliase[0]}'")->fetch_assoc();
    if ($group) {
        $result[mb_strtolower($group['url'])][] = $aliase[1];
    }
}
fclose($aliases);

$metrika = include "metrika.php";
$urls =[];
print_r($metrika['Заправка-картриджей']);
exit;
foreach ($metrika as $key => $count) {
    $urls[mb_strtolower($key)] = $count;
}



foreach ($result as $url => $groups) {
    if (isset($urls[$url])) {
        print "{$url}|{$urls[$url]}";
        $groups = array_unique($groups);
        foreach ($groups as $group) {
            print "|{$group}";
        }
        print PHP_EOL;
    }
}