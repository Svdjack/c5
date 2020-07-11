<?php

define('LANG_KEEP_CASE', 0);
define('LANG_LOWERCASE', 1);

$old_db = 'tvoyafirma';
$new_db = 'tvoyafirma_new';
$country = 1;                       // Идентификатор страны
$city_vid = 1;                      // Идентификатор словаря с городами/регионами
$category_vid = 2;                  // Идентификатор словаря с категориями
$relation_vid = 3;                  // Идентификатор словаря с ключами

$link = mysqli_connect('localhost', 'tvoyafirma', 'Gk`_-LbTLpRyp@5');
if (!$link) {
    die('CONNECT FAIL');
}

mysqli_set_charset($link, 'UTF8');

query("SET NAMES utf8");
query("SET CHARACTER SET 'utf8';");
query("SET SESSION collation_connection = 'utf8_general_ci';");

$default_worktime
    = '{"monday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"tuesday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"wednesday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"thursday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"friday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"saturday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"sunday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}}}';

$worktime_tmp = [
    'monday'    => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'tuesday'   => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'wednesday' => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'thursday'  => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'friday'    => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'saturday'  => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'sunday'    => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ]
];

$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'friday'];

$limit = 5000;
$total = 10000000;
$i = 0;
while ($i < $total) {
    $step = $i + $limit;
    $firms = query(
        "SELECT worktime.worktime, firm.name, firm.home, firm.street FROM 
    {$old_db}.firm_with_worktime AS firm
                      INNER JOIN {$old_db}.worktime ON firm.worktime_id = worktime.id
                    WHERE firm.id BETWEEN {$i} AND {$step}"
    );
    foreach ($firms as $n => $firm) {
        $firm['street'] = mysqli_escape_string($link, $firm['street']);
        $firm['home'] = mysqli_escape_string($link, $firm['home']);
        $firm['name'] = mysqli_escape_string($link, $firm['name']);
        $new_firm = query(
            "SELECT id FROM {$new_db}.firm WHERE name='{$firm['name']}' AND 
        street LIKE '%{$firm['street']}' AND home = '{$firm['home']}' and worktime = '{$default_worktime}'"
        )->fetch_assoc();
        if ($new_firm) {
            $new_wt = unserialize($firm['worktime']);
            $worktime = $worktime_tmp;
            foreach ($new_wt as $key => $value) {
                if (!empty($value[0])) {
                    if ($value[0] == 'Выходной') {
                        $worktime[$days[$key]]['type'] = 'rest';
                    } elseif ($value[0] == 'Круглосуточно') {
                        $worktime[$days[$key]]['type'] = 'nonstop';
                    } else {
                        $start_end = explode(':', $value[0]);
                        $worktime[$days[$key]]['start'] = $start_end[0];
                        $worktime[$days[$key]]['end'] = $start_end[1];
                        if (!empty($value[1])) {
                            $start_end = explode(':', $value[1]);
                            $worktime[$days[$key]]['type'] = 'normal_with_rest';
                            $worktime[$days[$key]]['obed']['start'] = $start_end[0];
                            $worktime[$days[$key]]['obed']['end'] = $start_end[1];
                        }
                    }
                }

            }
            set_worktime($new_firm['id'], $worktime);
        }
    }
    $i += $limit;
    print $i . PHP_EOL;
}


die();
$wt = query(
    "SELECT * 
FROM  {$old_db}.field_data_field_work_time
WHERE field_work_time_day_works_w_start
 OR field_work_time_day_weekend_1_w_start 
 OR field_work_time_day_weekend_2_w_start 
 OR field_work_time_day_all_w_start"
);

$worktime_tmp = [
    'monday'    => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'tuesday'   => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'wednesday' => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'thursday'  => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'friday'    => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'saturday'  => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ],
    'sunday'    => [
        'start' => '09:00',
        'end'   => '18:00',
        'type'  => 'normal',
        'obed'  => [
            'start' => '09:00',
            'end'   => '18:00'
        ]
    ]
];


foreach ($wt as $w) {
    $worktime = $worktime_tmp;
    if ($w['field_work_time_day_all_w_start']) {
        foreach ($worktime as $key => $value) {
            $worktime[$key]['start'] = $w['field_work_time_day_all_w_start'];
            $worktime[$key]['end'] = $w['field_work_time_day_all_w_end'];
        }
        if ($w['field_work_time_day_all_w_pause_start']) {
            foreach ($worktime as $key => $value) {
                $worktime[$key]['type'] = 'normal_with_rest';
                $worktime[$key]['obed']['start'] = $w['field_work_time_day_all_w_start'];
                $worktime[$key]['obed']['end'] = $w['field_work_time_day_all_w_end'];
            }
        }
        set_worktime($w['entity_id'], $worktime);
        continue;
    }
    if ($w['field_work_time_day_works_w_start']) {
        $worktime['monday']['start'] = $w['field_work_time_day_works_w_start'];
        $worktime['tuesday']['start'] = $w['field_work_time_day_works_w_start'];
        $worktime['wednesday']['start'] = $w['field_work_time_day_works_w_start'];
        $worktime['thursday']['start'] = $w['field_work_time_day_works_w_start'];
        $worktime['friday']['start'] = $w['field_work_time_day_works_w_start'];

        $worktime['monday']['end'] = $w['field_work_time_day_works_w_end'];
        $worktime['tuesday']['end'] = $w['field_work_time_day_works_w_end'];
        $worktime['wednesday']['end'] = $w['field_work_time_day_works_w_end'];
        $worktime['thursday']['end'] = $w['field_work_time_day_works_w_end'];
        $worktime['friday']['end'] = $w['field_work_time_day_works_w_end'];

        if ($w['field_work_time_day_works_w_pause_start']) {
            $worktime['monday']['type'] = 'normal_with_rest';
            $worktime['tuesday']['type'] = 'normal_with_rest';
            $worktime['wednesday']['type'] = 'normal_with_rest';
            $worktime['thursday']['type'] = 'normal_with_rest';
            $worktime['friday']['type'] = 'normal_with_rest';

            $worktime['monday']['obed']['start'] = $w['field_work_time_day_works_w_pause_start'];
            $worktime['tuesday']['obed']['start'] = $w['field_work_time_day_works_w_pause_start'];
            $worktime['wednesday']['obed']['start'] = $w['field_work_time_day_works_w_pause_start'];
            $worktime['thursday']['obed']['start'] = $w['field_work_time_day_works_w_pause_start'];
            $worktime['friday']['obed']['start'] = $w['field_work_time_day_works_w_pause_start'];

            $worktime['monday']['obed']['end'] = $w['field_work_time_day_works_w_pause_end'];
            $worktime['tuesday']['obed']['end'] = $w['field_work_time_day_works_w_pause_end'];
            $worktime['wednesday']['obed']['end'] = $w['field_work_time_day_works_w_pause_end'];
            $worktime['thursday']['obed']['end'] = $w['field_work_time_day_works_w_pause_end'];
            $worktime['friday']['obed']['end'] = $w['field_work_time_day_works_w_pause_end'];
        }

        if ($w['field_work_time_day_weekend_1_w_start']) {
            $worktime['saturday']['start'] = $w['field_work_time_day_weekend_1_w_start'];
            $worktime['saturday']['end'] = $w['field_work_time_day_weekend_1_w_end'];
            if ($w['field_work_time_day_weekend_1_w_pause_start']) {
                $worktime['saturday']['type'] = 'normal_with_rest';

                $worktime['saturday']['obed']['start'] = $w['field_work_time_day_weekend_1_w_start'];
                $worktime['saturday']['obed']['end'] = $w['field_work_time_day_weekend_1_w_end'];
            }
        } else {
            $worktime['saturday']['type'] = 'rest';
            print 'rest!';
            print "\n";
        }

        if ($w['field_work_time_day_weekend_2_w_start']) {
            $worktime['friday']['start'] = $w['field_work_time_day_weekend_2_w_start'];
            $worktime['friday']['end'] = $w['field_work_time_day_weekend_2_w_end'];
            if ($w['field_work_time_day_weekend_2_w_pause_start']) {
                $worktime['friday']['type'] = 'normal_with_rest';

                $worktime['friday']['obed']['start'] = $w['field_work_time_day_weekend_2_w_start'];
                $worktime['friday']['obed']['end'] = $w['field_work_time_day_weekend_2_w_end'];
            }
        } else {
            $worktime['sunday']['type'] = 'rest';
            print 'rest!';
            print "\n";
        }

        set_worktime($w['entity_id'], $worktime);
        continue;
    }
}


function set_worktime($id, $wt)
{
    global $new_db;
    $wt_json = json_encode($wt);
    query("UPDATE {$new_db}.firm SET worktime = '{$wt_json}' where id = '{$id}'");
    print "updated worktime for {$id}";
    print "\n";
}

function query($sql)
{
    global $link;
    $res = mysqli_query($link, $sql);
    if (!$res) {
        die(mysqli_error($link) . '<br/>' . $sql);
    }
    return $res;
}

function f($str)
{
    global $link;
    return "'" . mysqli_real_escape_string($link, trim($str)) . "'";
}