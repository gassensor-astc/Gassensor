<?php

namespace common\helpers;

class StringHelpers
{
    /**
     * @param $data
     * @return array
     */
    public static function ObjectToArray($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = self::ObjectToArray($value);
            }
            return $result;
        }
        return $data;
    }

    /**
     * @param string $str
     * @param int $chars
     * @return string
     */
    public static function shortText(string $str, int $chars = 500): string
    {
        $str = trim(preg_replace('/\s+/u', ' ', $str));
        if ($chars <= 0) {
            return '';
        }

        if (mb_strlen($str) <= $chars) {
            return $str;
        }

        // Берем чуть больше лимита и отрезаем по последнему слову.
        $cut = mb_substr($str, 0, $chars + 1);
        $lastSpace = mb_strrpos($cut, ' ');
        if ($lastSpace !== false && $lastSpace > (int)($chars * 0.6)) {
            $cut = mb_substr($cut, 0, $lastSpace);
        } else {
            $cut = mb_substr($cut, 0, $chars);
        }

        return rtrim($cut, " \t\n\r\0\x0B.,;:!-") . '...';
    }

    /**
     * @param $str
     * @return string
     */
    static public function removeHtmlTags($str)
    {
        $str = strip_tags($str);
        $str = html_entity_decode($str);

        return $str;
    }


    public static function slug(string $text, bool $toLower = true): string
    {
        $text = trim($text);

        $tr = [
            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Е" => "E",
            "Ё" => "E",
            "Ж" => "J",
            "З" => "Z",
            "И" => "I",
            "Й" => "Y",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "H",
            "Ц" => "TS",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SCH",
            "Ъ" => "",
            "Ы" => "YI",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "YU",
            "Я" => "YA",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "e",
            "ж" => "j",
            "з" => "z",
            "и" => "i",
            "й" => "y",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sch",
            "ъ" => "y",
            "ы" => "yi",
            "ь" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya",
            "«" => "",
            "»" => "",
            "№" => "",
            "Ӏ" => "",
            "’" => "",
            "ˮ" => "",
            "_" => "-",
            "'" => "",
            "`" => "",
            "^" => "",
            "\." => "",
            "," => "",
            ":" => "-",
            ";" => "",
            "<" => "",
            ">" => "",
            "!" => "",
            "\(" => "",
            "\)" => "",
            "%" => "-",
            "#" => "-",
        ];

        foreach ($tr as $ru => $en) {
            $text = mb_eregi_replace($ru, $en, $text);
        }

        if ($toLower) {
            $text = mb_strtolower($text);
        }

        $text = str_replace(' ', '-', $text);
        $text = urlencode($text);

        return $text;
    }

    /**
     * @param int $bytes
     * @param int $decimals
     * @return string
     */
    public static function humanFilesize(int $bytes, int $decimals = 2): string
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor] . 'б';
    }

}