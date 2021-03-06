<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 18.06.2018
 * Time: 9:27
 */

namespace API\Libraries;


class Str
{
    public $String;
    public $Words;


    public function in(string $Needle): bool
    {
        $str = stristr($this->String, $Needle);
        $result = (strlen($str) === 0 || !$str) ? false : true;
        return $result;
    }

    private function firstCharUpCustom(string $Str = null): string
    {
        $str = (!is_null($Str)) ? $Str : $this->String;
        $result = str_replace($str[0], strtoupper($str[0]), $str);
        return $result;
    }

    private function firstCharLowCustom(string $Str = null): string
    {
        $str = (!is_null($Str)) ? $Str : $this->String;
        $result = str_replace($str[0], strtolower($str[0]), $str);
        return $result;
    }

    public function firstCharUp(): self
    {
        $this->String = $this->firstCharUpCustom($this->String);
        return $this;
    }

    public function toUp (): self {
        $this->String = strtoupper($this->String);
        return $this;
    }

    public function toLow (): self {
        $this->String = strtolower($this->String);
        return $this;
    }

    public function explodeToWords(string $Pattern = "(\s)|[A-ZА-Я]|[~\!\.\-\_\/\=\+\,\?\\\+\-]", string $flags = "mu"): self
    {
        $str = $this->String;
        $Words = preg_split("/$Pattern/$flags", $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);
        foreach ($Words as $word) {
            $word = $str[$word[1]-1].$word[0];
            $this->Words[] =  trim($word);
        }
        return $this;
    }

    public function implodeWords(int $ConstIStr = 1, $CustomSeparator = null): self
    {
        $result = "";

        if ($ConstIStr == IStr::UpFirstChar) {
            foreach ($this->Words as $word) {
                $result .= $this->firstCharUpCustom($word);
            }
        } else if ($ConstIStr == IStr::UpFistCharAndLowOther) {
            foreach ($this->Words as $word) {
                $result .= $this->firstCharUpCustom(strtolower($word));
            }
        } else if ($ConstIStr == IStr::LowFirstChar) {
            foreach ($this->Words as $word) {
                $result .= $this->firstCharLowCustom($word);
            }
        } else if ($ConstIStr == IStr::LowFirstCharAndUpOther) {
            foreach ($this->Words as $word) {
                $result .= $this->firstCharLowCustom(strtoupper($word));
            }
        } else if ($ConstIStr == IStr::Custom && $CustomSeparator !== null) {
            $result = implode($CustomSeparator, $this->Words);
        }
        $this->String = $result;
        return $this;
    }

    public function SpaceOnWords(): self
    {
        $this->explodeToWords();
        $this->String = implode(" ", $this->Words);
        return $this;
    }

    public function fistCharUpAllWords(array $AdditionalCharsSeparators = null): self
    {

        $str = $this->String;
        $CharsSeparators = [" ", "_"];

        if (!is_null($AdditionalCharsSeparators)) {
            $CharsSeparators = array_merge($CharsSeparators, $AdditionalCharsSeparators);
        }

        $Pattern = "";
        foreach ($CharsSeparators as $Char) {
            $Pattern .= "(\\$Char)";
        }
        $Pattern = "/[$Pattern]/";
        $explode = preg_split($Pattern, $str);

        foreach ($explode as $word) {
            $str = str_replace($word, $this->firstCharUpCustom($word), $str);
        }

        $this->String = $str;

        return $this;

    }

    public function cleaning(array $CharList): self
    {
        foreach ($CharList as $Char) {
            $this->String = str_replace($Char, "", $this->String);
        }
        return $this;
    }

    public function replace(array $CharListSearch, string $Replace): self
    {
        foreach ($CharListSearch as $Char) {
            $this->String = str_replace($Char, $Replace, $this->String);
        }
        return $this;
    }

    public function intToWord($num = ''): string
    {
        $num = ($num != '') ? ( string )(( int )$num) : ((int)$this->String);

        $trim_all = function ($str, $what = NULL, $with = ' ') {
            if ($what === NULL) {
                //  Character      Decimal      Use
                //  "\0"            0           Null Character
                //  "\t"            9           Tab
                //  "\n"           10           New line
                //  "\x0B"         11           Vertical Tab
                //  "\r"           13           New Line in Mac
                //  " "            32           Space

                $what = "\\x00-\\x20";    //all white-spaces and control chars
            }

            return trim(preg_replace("/[" . $what . "]+/", $with, $str), $what);
        };
        $str_replace_last = function ($search, $replace, $str) {
            if (($pos = strrpos($str, $search)) !== false) {
                $search_length = strlen($search);
                $str = substr_replace($str, $replace, $pos, $search_length);
            }
            return $str;
        };

        if (( int )($num) && ctype_digit($num)) {
            $words = [];
            $num = str_replace([',', ' '], '', trim($num));
            $list1 = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
                'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
                'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];

            $list2 = ['', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
                'seventy', 'eighty', 'ninety', 'hundred'];

            $list3 = ['', 'thousand', 'million', 'billion', 'trillion',
                'quadrillion', 'quintillion', 'sextillion', 'septillion',
                'octillion', 'nonillion', 'decillion', 'undecillion',
                'duodecillion', 'tredecillion', 'quattuordecillion',
                'quindecillion', 'sexdecillion', 'septendecillion',
                'octodecillion', 'novemdecillion', 'vigintillion'];

            $num_length = strlen($num);
            $levels = ( int )(($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num = substr('00' . $num, -$max_length);
            $num_levels = str_split($num, 3);

            foreach ($num_levels as $num_part) {
                $levels--;
                $hundreds = ( int )($num_part / 100);
                $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
                $tens = ( int )($num_part % 100);
                $singles = '';

                if ($tens < 20) {
                    $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                } else {
                    $tens = ( int )($tens / 10);
                    $tens = ' ' . $list2[$tens] . ' ';
                    $singles = ( int )($num_part % 10);
                    $singles = ' ' . $list1[$singles] . ' ';
                }
                $words[] = $hundreds . $tens . $singles . (($levels && ( int )($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
            }

            $commas = count($words);
            $commas = ($commas > 1) ? $commas - 1 : $commas;
            $words = implode(', ', $words);
            $words = trim(str_replace(' ,', ',', $trim_all(ucwords($words))), ', ');
            if ($commas) {
                $words = $str_replace_last(',', ' and', $words);
            }

            return $words;
        } else if (!(( int )$num)) {
            return 'Zero';
        }
        return '';
    }

    public function floatToWord(float $num = null): string
    {
        $num = (!is_null($num)) ? $num : $this->String;
        $split = preg_split("/[(\.)(\,)]/", (string)$num);
        $result = "";
        foreach ($split as $Item) {
            $Item = $this->intToWord((string)$Item);
            $result .= "$Item And ";
        }

        $result = rtrim($result, "And ");
        return $result;
    }


    public function __construct(string $String)
    {
        $this->String = $String;
    }
}