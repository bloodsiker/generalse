<?php

namespace Umbrella\components;

/**
 * Class Functions
 */
class Functions
{
    // Обрезаем сообщение от пользователя
    public static function crop_str($string, $limit = '50')
    {
        if(strlen($string) > $limit) {
            $first = mb_substr($string, 0, $limit, 'UTF-8');
            return $first;
        } else {
            return $string;
        }
    }

    /**
     * Возращает дату в другом формате
     * @param $date
     * @return string
     */
    public static function formatDate($date)
    {
        return date_create($date)->Format('Y-m-d');
    }


    /**
     * Генерация случайно строки
     * @param int $length
     * @return bool|string
     */
    public static function generateCode($length = 10)
    {
        $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

        return $code;
    }

    /**
     * Хэшируем пароль
     * @param $password
     * @return string
     */
    public static function hashPass($password){
        $salt = substr(md5($password), 0, 5);
        $hash_pass = hash("sha256", $password . $salt);
        return $hash_pass;
    }

    /**
     * Тринслит с руского
     * @param $string
     * @return string
     */
    public static function rusTranslit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'і' => 'i',   'й' => 'y',
            'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'І' => 'I',   'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    /**
     * Формируем url alias
     * @param $str
     * @return mixed|string
     */
    public static  function strUrl($str) {
        // переводим в транслит
        $str = self::rusTranslit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }


    /**
     * Узнаем кол-во дней между двума датами
     * @param $start
     * @param $end
     * @return string
     */
    public static function calcDateEnd($start, $end)
    {
        $datetime1 = new \DateTime($start);
        $datetime2 = new \DateTime($end);
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%R%a');
    }

    /**
     * Кол-во секунд между датми
     * @param $date
     * @return false|int
     */
    public static function calcDiffSec($date)
    {
        $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($date);
        return $diff;
    }


    /** отнимаем -90 дней от даты
     * '-90 days'
     * @param $day
     * @param $count_day
     * @return bool|string
     */
    public static function addDays($day, $count_day)
    {
        $date = date_create($day);
        date_add($date, date_interval_create_from_date_string($count_day));
        return date_format($date, 'Y-m-d');
    }


    /**
     * Получаем день недели
     * @param $date
     * @return int
     */
    public static function whatDayOfTheWeekAndAdd($date)
    {
        $day = strftime("%a", strtotime($date));

        switch ($day){
            case 'Mon':
                $add_day = 15;
                break;
            case 'Tue':
                $add_day = 14;
                break;
            case 'Wed':
                $add_day = 13;
                break;
            case 'Thu':
                $add_day = 12;
                break;
            case 'Fri':
                $add_day = 11;
                break;
            case 'Sat':
                $add_day = 10;
                break;
            case 'Sun':
                $add_day = 9;
                break;
            default:
                $add_day = 15;
        }

        return self::addDays(date('Y-m-d'), "{$add_day} days");
    }


    /**
     * Сравнение количества товаров в поставке и резерве
     * @param $arg_1
     * @param $arg_2
     * @return string
     */
    public static function compareQuantity($arg_1, $arg_2)
    {
        if($arg_1 == $arg_2){
            return 'red';
        } else {
            return 'green';
        }
    }


    /**
     * Совпадение по поисковой строке - подсвечиваем
     * @param $search
     * @param $result
     * @return mixed
     */
    public static function replaceSearchResult($search, $result)
    {
        $search = iconv('WINDOWS-1251', 'UTF-8', $search);
        $result = iconv('WINDOWS-1251', 'UTF-8', $result);
        return preg_replace("/".$search."/i", "<b class='highlight'>".$search."</b>", $result);
    }


    /**
     * Из ассоциатиного массива удаляем елементы по повторяющихся ключах
     * @param $key
     * @param $array
     * @return array
     */
    public static function getUniqueArray($key, $array){
        $arrayKeys = array(); // массив для хранения ключей
        $resultArray = array(); // выходной массив
        foreach($array as $one){ // проходим циклом по всему исходному массиву
            if(!is_null($one[$key])){
                if(!in_array($one[$key], $arrayKeys)){ // если такого значения еще не встречаласть, то
                    $arrayKeys[] = $one[$key]; // пишем значение ключа в массив, для дальнейшей проверки
                    $resultArray[] = $one; // записываем уникальное значение в выходной массив
                }
            } else {
                $resultArray[] = $one;
            }
        }
        return $resultArray; // возвращаем массив
    }

}