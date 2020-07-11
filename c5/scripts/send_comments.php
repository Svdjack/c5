<?php

require_once __DIR__ . "/db.php";

query('USE tvoyafirma_new');

$comments = query('SELECT comment.*, comment.text AS comment, firm.name AS firm_name, firm.subtitle AS firm_subtitle, region.name AS city_name FROM comment 
                      INNER JOIN firm ON comment.firm_id = firm.id
                      INNER JOIN region ON region.id = firm.city_id');

foreach ($comments as $comment) {
    $result = send($comment);
    print ($result['status'] . PHP_EOL);
}


function send($data)
{
    $url = "kotel:rjnkj,fpf@kotel.spravka.today/comments/send";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $output = curl_exec($ch);
    curl_close($ch);

    return json_decode($output, JSON_OBJECT_AS_ARRAY);
}