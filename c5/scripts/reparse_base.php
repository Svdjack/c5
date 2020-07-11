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

// ===== Переносим регионы, города, рубрики и прочее =====

move_main();


////// ===== Переносим фирмы =====
//query("truncate $new_db.firm");
//$limit = 10000;
//$start = 20107;
//while ($start < 3000000) {
//    $time = microtime(true);
//    move_firms($start+1, intval($limit + $start));
//    $start = intval($limit + $start);
//    print ("###########################") . PHP_EOL;
//    print (microtime(true) - $time) . PHP_EOL;
//    print 'Memory in use: ' . memory_get_usage() . ' (' . ((memory_get_usage() / 1024) / 1024) . 'Mb)' . PHP_EOL;
//    print $start . PHP_EOL;
//    print ("###########################") . PHP_EOL;
//
//}
// query("truncate $new_db.firm_contacts");
//$limit = 500000;
//$start = 0;
//while (true) {
//    if (move_firms($start, $limit) == 0) {
//        break;
//    }
//    $start += $limit;
//}

// // ===== Переносим контакты =====
// move_contacts();

// ===== Other ===== TODO
/*query("
	update $new_db.city
	set city.count=(
		select count(*)
		from $new_db.firm
		where firm.city_id=city.id
	)
");*/

// меняем урлы
//change_urls();

// ставим главную категорию
//main_category();


print memory_get_usage() . '<br/>OK';

function change_urls()
{

    global $old_db;
    global $new_db;
    // алиасы для компаний
    $query = query("SELECT source,alias FROM $old_db.url_alias");
    $count = 0;
    $sql1 = '';
    foreach ($query as $item) {
        $source = explode('/', $item['source']);
        $id = $source[1];
        $alias = explode('/', $item['alias']);
        $url = end($alias);

        $count++;
        $sql1 .= "('/{$item['alias']}', '/firm/show/{$id}'),";
        if ($count == 1000) {
            query("INSERT INTO $new_db.url_aliases(alias,source) VALUES " . trim($sql1, ','));
            $sql1 = '';
            $count = 0;
        }

    }
    query("INSERT INTO $new_db.url_aliases(alias,source) VALUES " . trim($sql1, ','));
}

function move_main()
{
    global $old_db;
    global $new_db;
    global $country;
    global $city_vid;
    global $category_vid;
    global $relation_vid;

    ////переносим районы
//    query("truncate $new_db.district");
//
//    query(
//        "insert into $new_db.district
//  select tid as id, name as name FROM $old_db.taxonomy_term_data where vid = 7"
//    );
//
//    $query = query('SELECT id,name,city_id FROM');


    // переносим ключиs2

    query("truncate $new_db.tags");
//    query("truncate $new_db.firm_tags");
//
    query("insert into $new_db.tags
select tag.tid,tag.name,url.field_url_value
 from $old_db.taxonomy_term_data tag
 left join $old_db.field_data_field_url url on url.entity_id = tag.tid
  where tag.vid = $relation_vid and url.bundle = 'relation'");
//    query("insert into $new_db.firm_tags(firm_id, tag_id)
//select entity_id, field_relation_tid from $old_db.field_data_field_relation");


//    // // ===== Переносим регионы =====
//     query("truncate $new_db.region");
//     $query = query("
//     	select
//     	d.tid,
//     	d.name,
//     	u.field_url_value as url,
//     	n2.field_name_2_value as p2,
//     	n3.field_name_3_value as p3,
//     	n4.field_name_4_value as p4,
//     	n5.field_name_5_value as p5,
//     	n6.field_name_6_value as p6
//     	from $old_db.taxonomy_term_data d
//     	left join $old_db.taxonomy_term_hierarchy h on d.tid=h.tid
//     	left join $old_db.field_data_field_url u on d.tid=u.entity_id
//     	left join $old_db.field_data_field_name_2 n2 on d.tid=n2.entity_id
//     	left join $old_db.field_data_field_name_3 n3 on d.tid=n3.entity_id
//     	left join $old_db.field_data_field_name_4 n4 on d.tid=n4.entity_id
//     	left join $old_db.field_data_field_name_5 n5 on d.tid=n5.entity_id
//     	left join $old_db.field_data_field_name_6 n6 on d.tid=n6.entity_id
//     	where d.vid=$city_vid
//     	and h.parent=$country
//     ");
//     foreach($query as $row){
//     	// Падежи
//     	$data = array();
//     	$data['p2'] = $row['p2'];
//     	$data['p3'] = $row['p3'];
//     	$data['p4'] = $row['p4'];
//     	$data['p5'] = $row['p5'];
//     	$data['p6'] = $row['p6'];
//     	$sdata = serialize($data);
//     	// url
//     	$row['url'] = mb_strtolower($row['url'], 'utf8');
//     	// Заносим в новую базу
//     	query("
//     		insert into $new_db.region (id,name,url,data,sort) values (
//     			{$row['tid']},
//     			".$row['name'].",
//     			".f($row['url']).",
//     			".f($sdata).",
//     			0
//     		);
//     	");
//     }
//     unset($query);


//     // ===== Переносим города =====
//     query("truncate $new_db.region");
//     $query = query("
//     	select
//     	d.tid,
//     	h.parent,
//     	d.name,
//     	u.field_url_value as url,
//     	n2.field_name_2_value as p2,
//     	n3.field_name_3_value as p3,
//     	n4.field_name_4_value as p4,
//     	n5.field_name_5_value as p5,
//     	n6.field_name_6_value as p6,
//     	c.field_coords_value
//     	from $old_db.taxonomy_term_data d
//     	left join $old_db.taxonomy_term_hierarchy h on d.tid=h.tid
//     	left join $old_db.field_data_field_url u on d.tid=u.entity_id
//     	left join $old_db.field_data_field_name_2 n2 on d.tid=n2.entity_id
//     	left join $old_db.field_data_field_name_3 n3 on d.tid=n3.entity_id
//     	left join $old_db.field_data_field_name_4 n4 on d.tid=n4.entity_id
//     	left join $old_db.field_data_field_name_5 n5 on d.tid=n5.entity_id
//     	left join $old_db.field_data_field_name_6 n6 on d.tid=n6.entity_id
//     	left join $old_db.field_data_field_coords c on d.tid=c.entity_id and c.entity_type='taxonomy_term'
//     	where d.vid=$city_vid
//     	and h.parent>0
//     ");
//     foreach($query as $row){
////         print($row['name']);
//     	// Падежи
//     	$data = array();
//     	$data['p2'] = $row['p2'];
//     	$data['p3'] = $row['p3'];
//     	$data['p4'] = $row['p4'];
//     	$data['p5'] = $row['p5'];
//     	$data['p6'] = $row['p6'];
//     	$sdata = serialize($data);
//     	// url
//     	$row['url'] = mb_strtolower($row['url'],'utf8');
//     	$coords = explode(',',$row['field_coords_value']);
//     	$lon = 'null';
//     	$lat = 'null';
//     	if (count($coords)==2){
//     		$lon = '"'.trim($coords[0]).'"';
//     		$lat = '"'.trim($coords[1]).'"';
//     	}
//     	// Заносим в новую базу
//     	query("
//     		insert into $new_db.region (id,area,name,url,count,data,lon,lat) values (
//     			{$row['tid']},
//     			{$row['parent']},
//     			".f($row['name']).",
//     			".f($row['url']).",
//     			0,
//     			".f($sdata).",
//     			".$lon.",
//     			".$lat."
//     		);
//     	");
//     }
//     unset($query);
//
//    // ===== Переносим категории =====
//     query("truncate $new_db.groups");
//     $query = query("
//     	select
//     	d.tid,
//     	d.name,
//     	d.description,
//     	h.parent,
//     	u.field_url_value as url
//     	from $old_db.taxonomy_term_data d
//     	left join $old_db.taxonomy_term_hierarchy h on d.tid=h.tid
//     	left join $old_db.field_data_field_url u on d.tid=u.entity_id
//     	where d.vid=$category_vid
//     ");
//     foreach($query as $row){
//     	query("
//     		insert into $new_db.groups set
//     			id = {$row['tid']},
//     			parent = {$row['parent']},
//     			name = ".f($row['name']).",
//     			url = ".f($row['url']).",
//     			live = ".f($row['description']).";
//     	");
//     }
//     unset($query);
//
//
////     ===== Комментарии =====
//    query("truncate $new_db.comment");
//    $query = query(
//        "
//     	select
//     	c.cid as id,
//     	c.nid as firm_id,
//     	c.name as user,
//     	b.comment_body_value as comment,
//     	FROM_UNIXTIME(c.created) as created,
//     	c.hostname as ip,
//     	v.field_vote_rating as score,
//     	e.field_c_email_email as email,
//     	t.field_c_twitter_link_value as twitter,
//     	emo.field_emotion_value as emot
//     	from $old_db.comment c
//     	join $old_db.field_data_comment_body b on c.cid=b.entity_id
//     	left join $old_db.field_data_field_vote v on c.cid=v.entity_id
//     	left join $old_db.field_data_field_emotion emo on c.cid=emo.entity_id
//     	left join $old_db.field_data_field_c_email e on c.cid=e.entity_id
//     	left join $old_db.field_data_field_c_twitter_link t on c.cid=t.entity_id
//     	where c.status=1
//     "
//    );
//    foreach ($query as $row) {
//        $vote = $row['score'] / 20;
//        switch ($row['emot']) {
//            case 0:
//                $emot = 0;
//                break;
//            case 1:
//                $emot = 1;
//                break;
//            case 2:
//                $emot = 2;
//                break;
//            default:
//                $emot = 0;
//                break;
//        }
//        query(
//            "
//     		insert into $new_db.comment set
//     		id = {$row['id']},
//     		firm_id = {$row['firm_id']},
//     		user = " . f($row['user']) . ",
//     		email = " . f($row['email']) . ",
//     		text = " . f($row['comment']) . ",
//     		date = " . f($row['created']) . ",
//     		score = {$vote},
//     		ip = " . f($row['ip']) . ",
//     		edited = 0,
//     		emotion = '{$emot}',
//     		twitter = " . f($row['twitter']) . "
//     	"
//        );
//    }
//    unset($query);
//
//    // переносим чилдренов
//    query("truncate $new_db.firm_childs");
//    $query = query(
//        "
//    select * from $old_db.field_data_field_childrens
//    "
//    );
//
//    foreach ($query as $row) {
//        if (!empty($row['field_childrens_value'])) {
//            query(
//                "insert into $new_db.firm_childs SET
//             firm_id = {$row['entity_id']},
//             value = " . f($row['field_childrens_value'])
//            );
//        }
//    }
//
//    // переносим юр-данные
//    query("truncate $new_db.jur_data");
//    $query = query(
//        "SELECT
//  n.nid as firm_id,
//  COALESCE(cc.field_date_create_company_value,0) as create_date,
//  COALESCE(inn.field_inn_value,0) as inn,
//  COALESCE(kpp.field_kpp_value,0) as kpp,
//  COALESCE(ogrn.field_ogrn_value,0) as ogrn,
//  COALESCE(okpo.field_okpo_value,0) as okpo,
//  COALESCE(okato.field_okato_value,0) as okato,
//  COALESCE(fsfr.field_fsfr_value,0) as fsfr,
//  COALESCE(orgform.field_org_form_value,0) as org_form,
//  COALESCE(okogu.field_okogu_value,0) as okogu
//FROM $old_db.node n
//  LEFT JOIN $old_db.field_data_field_date_create_company cc ON n.nid = cc.entity_id
//  LEFT JOIN $old_db.field_data_field_inn inn ON n.nid = inn.entity_id
//  LEFT JOIN $old_db.field_data_field_kpp kpp ON n.nid = kpp.entity_id
//  LEFT JOIN $old_db.field_data_field_ogrn ogrn ON n.nid = ogrn.entity_id
//  LEFT JOIN $old_db.field_data_field_okpo okpo ON n.nid = okpo.entity_id
//  LEFT JOIN $old_db.field_data_field_okato okato ON n.nid = okato.entity_id
//  LEFT JOIN $old_db.field_data_field_fsfr fsfr ON n.nid = fsfr.entity_id
//  LEFT JOIN $old_db.field_data_field_org_form orgform ON n.nid = orgform.entity_id
//  LEFT JOIN $old_db.field_data_field_okogu okogu ON n.nid = okogu.entity_id"
//    );
//
//    foreach ($query as $item) {
//        $sql = '';
//        $i = 0;
//        foreach ($query as $row) {
//            $sql
//                .= "(null,
//                    null,
//                    {$row['firm_id']},
//                    null,
//                    null,
//                    null,
//                    null,
//                    null,
//                    null,
//                    null,
//                    " . f($row['inn']) . ",
//                    " . f($row['okato']) . ",
//                    " . f($row['fsfr']) . ",
//                    " . f($row['ogrn']) . ",
//                    " . f($row['okpo']) . ",
//                    " . f($row['org_form']) . ",
//                    " . f($row['okogu']) . ",
//                    " . f($row['create_date']) . ",
//                    null,
//                    null,
//                    null,
//                    " . f($row['kpp']) . "
//            ),";
//            $i++;
//            if ($i == 3000) {
//                query("insert into $new_db.jur_data values " . trim($sql, ','));
//                unset($sql);
//                $sql = '';
//                $i = 0;
//            }
//            unset($row);
//        }
//        if ($i > 0) {
//            query("insert into $new_db.jur_data values " . trim($sql, ','));
//            unset($sql);
//        }
//        unset($query);
//    }
//
//    // добавляем сериализованый оквед в юрдату
//
//    $query = query("SELECT entity_id as id, group_concat(field_okved_value SEPARATOR '----') as activ FROM $old_db.field_data_field_okved GROUP BY entity_id");
//    foreach ($query as $item) {
//        $activites = serialize(explode('----', $item['activ']));
//        query("UPDATE $new_db.jur_data SET activities='{$activites}' where firm_id = '{$item['id']}'");
//    }

    // переносим юзерские фотки

//    query("truncate $new_db.firm_photos;");
//    $query = query(
//        "SELECT ph.entity_id as id, fm.uri as photo, ph.field_photos_width as w, ph.field_photos_height as h
//    from $old_db.field_data_field_photos ph
//    left JOIN $old_db.file_managed fm ON fm.fid = ph.field_photos_fid"
//    );
//
//    foreach ($query as $row) {
//        // logo
//        $logo['n'] = str_replace('public://', '', $row['photo']);
//        $logo['w'] = $row['w'];
//        $logo['h'] = $row['h'];
//        $logo = serialize($logo);
//        query("insert into $new_db.firm_photos(firm_id,photo) values({$row['id']},'{$logo}')");
//        unset($logo);
//    }


     // ===== Пользователи =====
//     query("truncate $new_db.user;");
//     query("
//     	insert into $new_db.user
//     	select
//     	uid,
//     	name,
//     	pass,
//     	mail,
//     	name,
//     	0,
//     	'',
//     	0,
//     	''
//     	from $old_db.users
//     	where status=1
//     ");
//
//     query("truncate $new_db.firm_user;");
//     query("
//     	insert into $new_db.firm_user(firm_id, user_id)
//     	select
//     	nid,
//     	uid
//     	from $old_db.node
//     	where type='company'
//     ");
//
//
////    	// ===== Связь компания->категория =====
//        query("truncate $new_db.firm_group");
//        $query = query("
//            select distinct
//            n.nid as id,
//            COALESCE(ca.field_category_tid,0) as cat_id,
//            COALESCE(ci.field_city_tid,0) as city_id
//            from $old_db.node n
//            left join $old_db.field_data_field_category ca on n.nid=ca.entity_id and ca.entity_type='node'
//            left join $old_db.field_data_field_city ci on n.nid=ci.entity_id and ci.entity_type='node'
//            where n.type='company'
//            order by n.nid, ca.delta
//        ");
//        $sql = '';
//        $i = 0;
//        foreach($query as $row){
//            $sql .= "({$row['id']},{$row['cat_id']},{$row['city_id']}),";
//            $i++;
//            if ($i==30000){
//                query("insert into $new_db.firm_group values ".trim($sql,','));
//                unset($sql);
//                $sql = '';
//                $i = 0;
//            }
//            unset($row);
//        }
//        if ($i>0){
//            query("insert into $new_db.firm_group values ".trim($sql,','));
//            unset($sql);
//        }
//        unset($query);

}

function move_firms($start, $limit)
{
    global $old_db;
    global $new_db;
    $query = query(
        "
		select
		n.nid as id,
		n.title as name,
		from_unixtime(n.created) as created,
		COALESCE(ci.field_city_tid,0) as city_id,
		COALESCE(di.field_district_tid,0) as district_id,
		l.field_logo_width as logo_width,
		l.field_logo_height as logo_height,
		fm.uri as logo_name,
		ind.field_index_value as postal,
		sub.field_subtitle_value as subtitle,
		home.field_home_value as home,
		off.field_office_value as office,
		offn.field_offical_name_value as offical_name,
		s.field_street_value as street,
		db.body_value as descr,
		c.field_coords_value as coords,
		fc.field_category_tid as maincat,
		uas.alias as alias,
		wt.*
		from $old_db.node n
		left join $old_db.field_data_field_city ci on n.nid=ci.entity_id
		left join $old_db.field_data_field_street s on n.nid=s.entity_id
		left join $old_db.field_data_field_district di on n.nid=di.entity_id
		left join $old_db.field_data_field_index ind on n.nid=ind.entity_id
		left join $old_db.field_data_field_subtitle sub on n.nid=sub.entity_id
		left join $old_db.field_data_field_home home on n.nid=home.entity_id
		left join $old_db.field_data_field_office off on n.nid=off.entity_id
		left join $old_db.field_data_field_offical_name offn on n.nid=offn.entity_id
		left join $old_db.field_data_field_coords c on n.nid=c.entity_id and c.entity_type='node'
		left join $old_db.field_data_field_work_time wt on n.nid=wt.entity_id
		left join $old_db.field_data_field_logo l on n.nid=l.entity_id
		left join $old_db.file_managed fm on l.field_logo_fid=fm.fid
		left join $old_db.field_data_body db on n.nid=db.entity_id
		left join $old_db.field_data_field_category fc on n.nid=fc.entity_id and fc.delta = 0
		left join $old_db.url_alias uas on concat('node/',n.nid) = uas.source
		where n.type='company'
		and n.nid BETWEEN $start and $limit
	"
    );
    $sql1 = '';
    $sql2 = '';
    $i1 = 0;
    $i2 = 0;
    foreach ($query as $row) {
        // logo
        $logo['n'] = str_replace('public://', '', $row['logo_name']);
        $logo['w'] = $row['logo_width'];
        $logo['h'] = $row['logo_height'];
        // url
//        $url = explode('/', mb_strtolower($row['url']));
//        $row['url'] = $url[count($url) - 1];

        // Рабочее время
        $work['wo_s'] = $row['field_work_time_day_works_w_start'];
        $work['wo_e'] = $row['field_work_time_day_works_w_end'];
        $work['wo_p_s'] = $row['field_work_time_day_works_w_pause_start'];
        $work['wo_p_e'] = $row['field_work_time_day_works_w_pause_end'];
        $work['we1_s'] = $row['field_work_time_day_weekend_1_w_start'];
        $work['we1_e'] = $row['field_work_time_day_weekend_1_w_end'];
        $work['we1_p_s'] = $row['field_work_time_day_weekend_1_w_pause_start'];
        $work['we1_p_e'] = $row['field_work_time_day_weekend_1_w_pause_end'];
        $work['we2_s'] = $row['field_work_time_day_weekend_2_w_start'];
        $work['we2_e'] = $row['field_work_time_day_weekend_2_w_end'];
        $work['we2_p_s'] = $row['field_work_time_day_weekend_2_w_pause_start'];
        $work['we2_p_e'] = $row['field_work_time_day_weekend_2_w_pause_end'];
        $work['a_s'] = $row['field_work_time_day_all_w_start'];
        $work['a_e'] = $row['field_work_time_day_all_w_end'];
        $work['a_p_s'] = $row['field_work_time_day_all_w_pause_start'];
        $work['a_p_e'] = $row['field_work_time_day_all_w_pause_end'];

        $work = array_diff($work, array(null));

        $work = '
        {
            monday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            },
            tuesday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            },
            wednesday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            },
            thursday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            },
            friday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            },
            saturday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            },
            sunday: {
                start: \'09:00\',
                end: \'18:00\',
                type: \'normal\',
                obed: {
                    start: \'12:00\',
                    end: \'13:00\'
                }
            }
        }
        ';

        $work = '{}';


        // Координаты
        $lon = 0;
        $lat = 0;
        $coords = explode(',', $row['coords']);
        if (count($coords) == 2) {
            $lon = trim($coords[0]);
            $lat = trim($coords[1]);
        }
        if (empty($lon)) {
            $lon = 0;
        }
        if (empty($lat)) {
            $lat = 0;
        }
        $alias = explode('/', $row['alias']);
        $url = end($alias);

        $sql1
            .= "
				({$row['id']},
				1,
				1,
				0,
				" . f($row['name']) . ",
				" . f($row['offical_name']) . ",
				" . f($url) . ",
				" . f($row['subtitle']) . ",
				" . f($row['descr']) . ",
				" . f($row['postal']) . ",
				" . f($row['district_id']) . ",
				" . f($row['street']) . ",
				{$row['city_id']},
				" . f($row['street']) . ",
				" . f($row['home']) . ",
				" . f($row['office']) . ",
				" . f($row['maincat']) . ",
				" . f($work) . ",
				0,
				0,
				0,
				0,
				$lon,
				$lat,
				0,
				" . f($logo['n']) . "
				),";
        $i1++;
        if ($i1 == 10000) {
            query("replace into $new_db.firm values " . trim($sql1, ','));
            unset($sql1);
            $sql1 = '';
            $i1 = 0;
        }

        unset($logo);
        unset($url);
        unset($work);
        unset($coords);
        unset($lon);
        unset($lat);
        unset($row);
    }
    if ($i1 > 0) {
        query("replace into $new_db.firm values " . trim($sql1, ','));
        unset($sql1);
        $sql1 = '';
        $i1 = 0;
    }
    return mysqli_num_rows($query);
}

function move_contacts()
{
    global $old_db;
    global $new_db;

    query("truncate $new_db.firm_contacts");

    // Факс
    $query = query(
        "
            select
            entity_id as firm_id,
            field_fax_value as value
            from $old_db.field_data_field_fax
            where bundle='company'
            and deleted=0
	"
    );
    $sql = '';
    $i = 0;
    foreach ($query as $row) {
        $sql .= "({$row['firm_id']},'fax'," . f($row['value']) . "),";
        $i++;
        if ($i == 10000) {
            query("insert into $new_db.firm_contacts values " . trim($sql, ','));
            $sql = '';
            $i = 0;
        }
    }
    if ($i > 0) {
        query("insert into $new_db.firm_contacts values " . trim($sql, ','));
        $sql = '';
        $i = 0;
    }

    // Телефон
    $query = query(
        "
		select
		entity_id as firm_id,
		field_phone_value as value
		from $old_db.field_data_field_phone
		where bundle='company'
		and deleted=0
	"
    );
    $sql = '';
    $i = 0;
    foreach ($query as $row) {
        $sql .= "({$row['firm_id']},'phone'," . f($row['value']) . "),";
        $i++;
        if ($i == 10000) {
            query("insert into $new_db.firm_contacts values " . trim($sql, ','));
            $sql = '';
            $i = 0;
        }
    }
    if ($i > 0) {
        query("insert into $new_db.firm_contacts values " . trim($sql, ','));
        $sql = '';
        $i = 0;
    }

    // Сайт
    $query = query(
        "
		select
		entity_id as firm_id,
		field_website_value as value
		from $old_db.field_data_field_website
		where bundle='company'
		and deleted=0
	"
    );
    $sql = '';
    $i = 0;
    foreach ($query as $row) {
        $sql .= "({$row['firm_id']},'website'," . f($row['value']) . "),";
        $i++;
        if ($i == 10000) {
            query("insert into $new_db.firm_contacts values " . trim($sql, ','));
            $sql = '';
            $i = 0;
        }
    }
    if ($i > 0) {
        query("insert into $new_db.firm_contacts values " . trim($sql, ','));
        $sql = '';
        $i = 0;
    }

    // Email
    $query = query(
        "
		select
		entity_id as firm_id,
		field_mail_email as value
		from $old_db.field_data_field_mail
		where bundle='company'
		and deleted=0
	"
    );
    $sql = '';
    $i = 0;
    foreach ($query as $row) {
        $sql .= "({$row['firm_id']},'email'," . f($row['value']) . "),";
        $i++;
        if ($i == 10000) {
            query("insert into $new_db.firm_contacts values " . trim($sql, ','));
            $sql = '';
            $i = 0;
        }
    }
    if ($i > 0) {
        query("insert into $new_db.firm_contacts values " . trim($sql, ','));
        $sql = '';
        $i = 0;
    }
}

function f($str)
{
    global $link;
    return "'" . mysqli_real_escape_string($link, trim($str)) . "'";
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


function toUrl($street)
{
    $street = trim($street);
    $street = str_replace('.', '', $street);
    $street = str_replace(' ', '-', $street);
    $street = strtolower(toEng($street));
    return $street;
}

function toEng($string, $case = LANG_KEEP_CASE)
{
    $translate = '';

    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю',
                 'Я',
                 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю',
                 'я');
    if ($case == LANG_LOWERCASE) {
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e',
                     'yu', 'ya',
                     'a', 'b', 'v', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e',
                     'yu', 'ya');
    } else {
        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'J', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E',
                     'Yu', 'Ya',
                     'a', 'b', 'v', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e',
                     'yu', 'ya');
    }
    $translate = str_replace($rus, $lat, $string);
    $translate = preg_replace('/[^A-Za-z0-9]/', '_', $translate);
    $translate = preg_replace('/_+/', '_', $translate);

    return $translate;
}









/*query("
	update $old_db.city,$new_db.city
	set $new_db.city.lon=$old_db.city.lon,
	$new_db.city.lat=$old_db.city.lat
	where $new_db.city.id=$old_db.city.id
");*/


/* Получаем координаты для города
$query = query("
	select
	city.id,
	region.name as region_name,
	city.name as city_name
	from $new_db.region
	join $new_db.city on region.id=city.region
");
foreach($query as $row){
	$url = 'https://geocode-maps.yandex.ru/1.x/?geocode=Россия, '.$row['region_name'].', '.$row['city_name'];
    $url = str_replace(' ', '%20', $url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $content = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) {
        die('No donuts for you.');
    }

	preg_match('/<pos>(.*?)<\/pos>/',$content,$point);
	$coord = explode(' ', trim(strip_tags($point[1])));
	$lon = $coord[0];
	$lat = $coord[1];

	query("update $new_db.city set lon={$lon}, lat={$lat} where id={$row['id']}");
}*/