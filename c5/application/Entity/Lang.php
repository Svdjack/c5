<?php



////
//	Список стоп-слов для Ad-Sense.
//	Данный файл можно включать инклудом в любой проект и проверять текст на наличие стоп-слов функцией bool adsense_check_text(mixed $text)
//	Объекты следует передавать используя преобразование типов (array)
//	@requirements: PHP 5.3
//	С PHP<5.3 можно использовать только на строки, так как нет поддержки lambda функций.
//	@return: const ADSENSE_CHECK_DRUGS если пользователь ввел нелегальщину
//	@return: const ADSENSE_CHECK_PRON если пользователь ввел контент для взрослых
//	@return: FALSE если вхождение не найдено
////


namespace wMVC\Entity;

use Exception;

Class Lang
{
    const LANG_KEEP_CASE = 0;
    const LANG_LOWERCASE = 1;

    public static function ucfirst($string, $enc = 'UTF-8')
    {
        if (!strlen($string)) return false;

        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) . mb_strtolower(mb_substr($string, 1, mb_strlen($string, $enc)), $enc);

    }

    public static function ucfirst_leave_rest($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) . mb_substr($string, 1, mb_strlen($string, $enc));
    }

    public static function suffix($number, $end_ro, $end_im, $end_vi)
    {
        $number = $number % 100;
        if ($number >= 11 && $number <= 14) {
            return $end_ro;
        } else {
            $rest = $number % 10;
            if ($rest == 1) {
                return $end_im;
            } elseif ($rest == 2 || $rest == 3 || $rest == 4) {
                return $end_vi;
            } else {
                return $end_ro;
            }
        }
    }

    public static function transliterate($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );

        return strtr($string, $converter);
    }

    public static function toUrl($street)
    {
        $street = trim($street);
        $street = str_replace('.', '', $street);
        $street = str_replace(' ', '-', $street);
        $street = strtolower(self::urlify($street));
        return $street;
    }

    public static function urlify($string){
        $string = preg_replace('/[^A-Za-z0-9А-Яа-я]/u', '_', $string);
        $string = preg_replace('/_+/', '_', $string);
        return $string;
    }

    public static function toEng($string, $case = self::LANG_KEEP_CASE)
    {
        $translate = '';

        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
                     'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю',
                     'Я',
                     'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р',
                     'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю',
                     'я');
        if ($case == self::LANG_LOWERCASE) {
            $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p',
                         'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e',
                         'yu', 'ya',
                         'a', 'b', 'v', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
                         'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e',
                         'yu', 'ya');
        } else {
            $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'J', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
                         'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E',
                         'Yu', 'Ya',
                         'a', 'b', 'v', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
                         'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e',
                         'yu', 'ya');
        }
        $translate = str_replace($rus, $lat, $string);
        $translate = preg_replace('/[^A-Za-z0-9]/', '_', $translate);
        $translate = preg_replace('/_+/', '_', $translate);

        return $translate;
    }

    public static function rip_tags($string) {

        // ----- remove HTML TAGs -----
        $string = preg_replace ('/<[^>]*>/', ' ', $string);

        // ----- remove control characters -----
        $string = str_replace("\r", '', $string);    // --- replace with empty space
        $string = str_replace("\n", ' ', $string);   // --- replace with space
        $string = str_replace("\t", ' ', $string);   // --- replace with space

        // ----- remove multiple spaces -----
        $string = trim(preg_replace('/ {2,}/', ' ', $string));

        return $string;

    }
    
    public static function needShow() {
        $content = \base64_encode(\json_encode(func_get_args(), JSON_UNESCAPED_UNICODE));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://test.companyru.ru/adv-badwords");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'text' => $content
        ]));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        $server_output = curl_exec($ch);

        curl_close($ch);

        try {
            $json = \json_decode($server_output, true, 512);
            return ($json['err'] == false ? 0 : $json['reason_code']);
        } catch (\Exception $ex) {
            DEBUG && var_dump($ex->getMessage());
            return 1;
        }
    }

    public static function adsense_check_text()
    {
        $n = self::needShow(func_get_args());
        $text = \print_r(func_get_args(), true);

        if ($n !== 1) {
            return $n;
        }

        $bad_words = array(
            ADSENSE_CHECK_DRUGS =>
                array( // drugs and other illegal stuff
                    'табак',
                    'табач',
                    'сигарет',
                    'секс',
                    'эротич',
                       "педофил",
                       "дерьмо",
                       "бляд",
                       "гавн",
                       "сучий",
                       "пидор",
                       "сука",
                       "хуе",
                       "член",
                       "шлюх",
                       "хуй",
                       "xx",
                       "johnson",
                       "planker",
                       "bum",
                       "gambling",
                       "casino",
                       "las vegas",
                       "poker",
                       "schlong",
                       "cocaine",
                       "marijuana",
                       "heroin",
                       "abortion",
                       "cancer",
                       "stroke",
                       "die",
                       "deceased",
                       "dead",
                       "death",
                       "dying",
                       "funeral",
                       "casket",
                       "burial",
                       "perished",
                       "drowned",
                       "electrocuted",
                       "electrocution",
                       "killer",
                       "killed",
                       "murder",
                       "murderer",
                       "murdered",
                       "suicide",
                       "slaughtered",
                       "manslaughter",
                       "poisoned",
                       "poisoning",
                       "strangulationm",
                       "suffocation",
                       "medication",
                       "morphine",
                       "overdose",
                       "oxycontin",
                       "oxycodone",
                       "pharmacy",
                       "pharmaceutical",
                       "aneurysm",
                       "hemorrhage",
                       "maimed",
                       "paralyzed",
                       "miscarriage",
                       "embolism",
                       "horror",
                       "bomb",
                       "bomber",
                       "terrorist",
                       "attack",
                       "disfigured",
                       "cerebral",
                       "prison",
                       "jail",
                       "incarcerated",
                       "взлом",
                       "хакер",
                       "crack",
                       "Keygen",
                       "спам",
                       "google",
                       "азарт",
                       "покер",
                       "казино",
                       "kasino",
                       "cazino",
                       "kazino",
                       "pokker",
                       "поккер",
                       "игровые автоматы",
                       "игровой автомат",
                       "игральные автоматы",
                       "игральный автомат",
                       "бомб",
                       "нападени",
                       "террор",
                       "терор",
                       "динамит",
                       "пластин",
                       "детонатор",
                       "взрыв",
                       "тиррор",
                       "тирор",
                       "захоронени",
                       "похороны",
                       "мертв",
                       "мёртв",
                       "погиб",
                       "смерт",
                       "умер",
                       "manslaughter ",
                       "miscaniage",
                       "дышител",
                       "отравл",
                       "самоубийств",
                       "казн",
                       "суецид",
                       "убий",
                       "убит",
                       "удуш",
                       "desanguinated",
                       "аборт",
                       "авария",
                       "аневризм",
                       "изуродован",
                       "инсульт",
                       "искалечен",
                       "карцином",
                       "кровоизлияни",
                       "ожог",
                       "парализован",
                       "побои",
                       "ужас",
                       "эмболи",
                       "увечи",
                       "побой",
                       "сарком",
                       "demise",
                       "expire",
                       "fatal stroke",
                       "past away",
                       "drown",
                       "execution",
                       "posioned",
                       "strangler",
                       "strangulation",
                       "drugs",
                       "burn",
                       "crash",
                       "cerebral accident",
                       "video poker",
                       "jah rush",
                       "jahrush",
                       "поперс",
                       "popers",
                       "gvh",
                       "gwh",
                       "JWH",
                       "poppers",
                       "spice",
                       "spike",
                       "амфетамин",
                       "благовония",
                       "гашиш",
                       "героин",
                       "гидропон",
                       "гидропоник",
                       "конопл",
                       "курител",
                       "нарк",
                       "лаванд",
                       "левкой",
                       "легалк",
                       "легальны",
                       "первитин",
                       "попперс",
                       "розмарин",
                       "сальвия",
                       "сканк",
                       "спай",
                       "спайк",
                       "спайс",
                       "спейс",
                       "спэйс",
                       "шалфе",
                       "энтеоген",
                       "Кокаин",
                       "марихуан",
                       "морфин",
                       "оксикодон",
                       "оксиконтин"
                ),
            ADSENSE_CHECK_PORN  =>
                array(
                    "пенис",
                    "фаллоc",
                    "виагр",
                    "вибратор",
                    "секс",
                    "гей",
                    "дилдо",
                    "интим",
                    "гавен",
                    "камасутр",
                    "лесби",
                    "лизби",
                    "мастурбатор",
                    "оральн",
                    "оргазм",
                    "порно",
                    "проститутк",
                    "ролев",
                    "самотык",
                    "фалоc",
                    "фалоимитатор",
                    "соблазнени",
                    "фаллоимитатор",
                    "хентай",
                    "эротик",
                    "эротич",
                    "эрекци",
                    "bitch",
                    "whore",
                    "whoredom",
                    "slut",
                    "sluts",
                    "sexually",
                    "dildo",
                    "viagra",
                    "ХХ",
                    "XX",
                    " анал",
                    "вагин",
                    "мастурб",
                    "XX",
                    "masturbation",
                    "voyeur",
                    "transvestite",
                    "transsexual",
                    "transsexuals",
                    "STD",
                    "S&M",
                    "orgy",
                    "masochism",
                    "hedonist",
                    "sex",
                    "homosexual",
                    "fetish",
                    "fellatio",
                    "exhibitionist",
                    "cunninglus",
                    "bondage",
                    "BDSM",
                    "analingus",
                    "adult",
                    'AC/DC',
                    "bisexual",
                    "pornographic",
                    "anus",
                    "licking",
                    "sucking",
                    "sadomasochism",
                    "sexual",
                    "vagina",
                    "clitoris",
                    "penis",
                    "arousal",
                    "lesbian",
                    "anal",
                    "condom",
                    "sadism",
                    "sexuality",
                    "fuck",
                    "porn",
                    "shit",
                    "cunt",
                    "cocksucker",
                    "tits",
                    "nude",
                    "naked",
                    "dick",
                    "lesbi",
                    "xxx",
                    "pedophile",
                    "pedophilia",
                    "erection",
                    "pussy",
                )
        );
        if (is_string($text)) {
            foreach ($bad_words[ADSENSE_CHECK_DRUGS] as $word) {
                if (mb_stristr($text, $word)) {
                    return ADSENSE_CHECK_DRUGS;
                }
            }
            foreach ($bad_words[ADSENSE_CHECK_PORN] as $word) {
                if (mb_stristr($text, $word)) {
                    return ADSENSE_CHECK_PORN;
                }
            }
        }
        return false;
    }

    public static function kotel_worktime($worktime)
    {
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

    public static function kotel_worktime_to_gis($worktime)
    {
        $worktime = json_decode($worktime);
        $gis_worktime = [];

        $days_orig = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];

        foreach($worktime as $day_name => $data){
            if($data->type == 'rest'){
                continue;
            }

            if($data->type == 'nonstop'){
                $gis_worktime[$days_orig[$day_name]]['working_hours'][0]['from'] = '0:00';
                $gis_worktime[$days_orig[$day_name]]['working_hours'][0]['to'] = '24:00';
                continue;
            }

            $gis_worktime[$days_orig[$day_name]]['working_hours'][0]['from'] = $data->start;
            $gis_worktime[$days_orig[$day_name]]['working_hours'][0]['to'] = $data->end;
        }

        return serialize($gis_worktime);
    }

    public static function switchKeyboard($string)
    {
        $lat = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', "'", 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.',
            'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', '{', '}', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', ':', '"', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '<', '>'];
        $rus = ['й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю',
            'Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ', 'Ф', 'Ы', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю'];

        $translate = str_replace($lat, $rus, $string);
        return $translate;
    }
}