<?php

require_once __DIR__.'/db.php';



$step = 1000;
$max = 4000000;
$start = 0;
$i = 0;
$count = 0;
$count_found = 0;
//
////fixin shit...
//
//
//$firms_to_fix = query(
//    "SELECT firm.name, firm.subtitle, firm.official_name, firm.building, street.name AS street, postal, firm.worktime
//FROM  tvoyafirma_valet.firm
//LEFT JOIN tvoyafirma_valet.street ON firm.street_id = street.id
//WHERE worktime NOT LIKE  '%Mon%' and worktime is NOT NULL
//"
//);
//
//foreach($firms_to_fix as $firm){
//    $count++;
//
//    $firmn = query("SELECT id FROM tvoyafirma_new.firm where
//                  name = '".htmlspecialchars($firm['name'], ENT_QUOTES)."'
//              and street = '{$firm['street']}'
//              and subtitle = '".htmlspecialchars($firm['subtitle'], ENT_QUOTES)."'
//              and home = '{$firm['building']}'
//              and postal = '{$firm['postal']}'")->fetch_assoc();
//
//
//
//    if ($firmn['id']){
//        $count_found++;
//        query("UPDATE tvoyafirma_new.firm SET worktime = '".reformWorktime($firm['worktime'])."' where id = '{$firmn['id']}'");
//        print "({$count_found}/{$count})updooted time".PHP_EOL;
//    }else{
//        print "({$count_found}/{$count})NOT FOUND??".PHP_EOL;
//    }
//
//}
//
//die();

for ($i = $start; $i < $max; $i = $i + $step) {
    $begin = $i - $step;

    $starting_point = 0;
    if($begin > 0){
        $starting_point = $begin;
    }

    print "starting at $starting_point, goin for $step firms...".PHP_EOL;

    $firms = query("
    SELECT firm.name name, firm.subtitle sub, firm.official_name off, firm.id id, region.name city, firm.street street, firm.home home, firm.postal postal
    FROM tvoyafirma_new.firm
    LEFT JOIN tvoyafirma_new.region ON firm.city_id = region.id
    LEFT JOIN tvoyafirma_new.firm_user fu ON fu.firm_id = firm.id
    where firm.id BETWEEN $starting_point and ".($starting_point + $step)." and firm.worktime = ''
    
    ");




    foreach ($firms AS $firm) {

        $firm['name'] = htmlspecialchars(addslashes($firm['name']), ENT_QUOTES);
        $firm['sub'] = htmlspecialchars(addslashes($firm['sub']), ENT_QUOTES);
        $firm['phone'] = preg_replace("/[^0-9]/", "", $firm['phone']);
        $firm['phone'] = '+7'.substr($firm['phone'], -10);


        $new_firm = query("SELECT firm.worktime FROM tvoyafirma_valet.firm 
                            LEFT JOIN tvoyafirma_valet.city ON firm.city_id = city.id 
                            LEFT JOIN tvoyafirma_valet.firm_contacts ON firm_contacts.firm_id = firm.id and firm_contacts.type = 'mobile'
                            LEFT JOIN tvoyafirma_valet.street ON street.id = firm.street_id
                           WHERE firm.name = '{$firm['name']}' and firm.postal = '{$firm['postal']}' and firm.building = '{$firm['home']}' AND city.name = '{$firm['city']}' AND firm.worktime IS NOT NULL")
            ->fetch_assoc();
        $count++;
        if ($new_firm) {
            $count_found++;
            $worktime = reformWorktime($new_firm['worktime']);

//            print_r($worktime);

            query("UPDATE tvoyafirma_new.firm SET worktime = '{$worktime}' WHERE id = {$firm['id']}");


            print "($count_found/$count) found worktime".PHP_EOL;
        }else{
            print "($count_found/$count) found nothing".PHP_EOL;
        }
    }
//    print $count . PHP_EOL;
}

function reformWorktime($worktime){
    $worktime_tmp = [
        'monday'    => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ],
        'tuesday'   => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ],
        'wednesday' => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ],
        'thursday'  => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ],
        'friday'    => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ],
        'saturday'  => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ],
        'sunday'    => [
            'start' => '09:00',
            'end'   => '18:00',
            'type'  => 'rest',
            'obed'  => [
                'start' => '09:00',
                'end'   => '18:00'
            ]
        ]
    ];

    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];


    $days_orig = ['Mon' => 'monday', 'Tue' => 'tuesday', 'Wed' => 'wednesday', 'Thu' => 'thursday', 'Fri' => 'friday', 'Sat' => 'saturday', 'Sun' => 'sunday'];


    $worktime = unserialize($worktime);
    $new_worktime = $worktime_tmp;

    foreach ($worktime as $day => $value) {
        if (!empty($value['working_hours'])) {
            $value = $value['working_hours'];
            if (count($value) == 1) {
                $value = reset($value);

                $new_worktime[$days_orig[$day]]['start'] = explode(':', $value['from'])[0].":00";
                $new_worktime[$days_orig[$day]]['end'] = explode(':', $value['to'])[0].":00";
                $new_worktime[$days_orig[$day]]['type'] = "normal";


            } else {
                $from = array_shift($value);
                $to = array_pop($value);

                $new_worktime[$days_orig[$day]]['type'] = "normal_with_rest";

                $new_worktime[$days_orig[$day]]['start'] = explode(':', $from['from'])[0].":00";
                $new_worktime[$days_orig[$day]]['end'] = explode(':', $to['to'])[0].":00";

                $new_worktime[$days_orig[$day]]['obed']['start'] = explode(':', $from['to'])[0].":00";
                $new_worktime[$days_orig[$day]]['obed']['end'] = explode(':', $to['from'])[0].":00";



            }
        } else {
            $new_worktime[$days_orig[$day]]['type'] = "rest";
        }
    }


    $new_worktime = json_encode($new_worktime, JSON_UNESCAPED_UNICODE);

    return $new_worktime;
}