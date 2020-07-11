<?php

require_once "db.php";

query('use tvoyafirma_new');

$doubles = query(
    'select name, official_name, subtitle, postal, street, home, office, city_id, count(*) c from firm
  group by name, official_name, subtitle, postal, street, home, office, city_id having c > 1 order by id DESC'
);

$count = 0;

foreach ($doubles as $doulbe) {
    $count++;

    $this_doubles = query("
        select id from firm where
        name = '{$doulbe['name']}'
        and official_name = '{$doulbe['official_name']}'
        and subtitle = '{$doulbe['subtitle']}'
        and city_id = '{$doulbe['city_id']}'
        and postal = '{$doulbe['postal']}'
        and street = '{$doulbe['street']}'
        and home = '{$doulbe['home']}'
        and office = '{$doulbe['office']}'
        order by id DESC
    ");

    $i = 0;
    $ideal_id = 0;
    foreach($this_doubles as $acc){
        if($i++ == 0){
            $ideal_id = $acc['id'];
        }
        else{
            query("UPDATE firm SET redirect_id = '{$ideal_id}' where id = '{$acc['id']}'");
            echo "set {$ideal_id} for {$acc['id']}".PHP_EOL;
        }
    }

}

print ($count);