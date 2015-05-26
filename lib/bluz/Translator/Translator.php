<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Translator;

use Bluz\Common\Options;
use Bluz\Config\ConfigException;

/**
 * Translator
 * based on gettext library
 *
 * @category Bluz
 * @package  Translator
 *
 * @author   Anton Shevchuk
 * @created  23.04.13 16:37
 */
class Translator extends Options
{
    //use Options;

    /**
     * @see http://www.loc.gov/standards/iso639-2/php/code_list.php
     * @var string
     */
    protected $locale = 'en_US';

    /**
     * @var string
     */
    protected $domain = 'messages';

    /**
     * @var string
     */
    protected $path;

    /**
     * set domain
     *
     * @param string $domain
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * set locale
     *
     * @param string $locale
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * set path to l10n
     *
     * @param string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Initialization
     *
     * @throw \Bluz\Config\ConfigException
     * @return boolean
     */
    protected function initOptions()
    {
        // Setup locale
        putenv('LC_ALL=' . $this->locale);
        putenv('LANG=' . $this->locale);
        putenv('LANGUAGE=' . $this->locale);

        // Windows workaround
        if (!defined('LC_MESSAGES')) {
            define('LC_MESSAGES', 6);
        }

        setlocale(LC_MESSAGES, $this->locale);

        // For gettext only
        if (function_exists('gettext')) {
            // Setup domain path
            $this->addTextDomain($this->domain, $this->path);

            // Setup default domain
            textdomain($this->domain);
        }
    }

    /**
     * add text domain for gettext
     *
     * @param string $domain of text for gettext setup
     * @param string $path on filesystem
     * @throws ConfigException
     * @return self
     */
    public function addTextDomain($domain, $path)
    {
        // check path
        if (!is_dir($path)) {
            throw new ConfigException("Translator configuration path `$path` not found");
        }

        bindtextdomain($domain, $path);

        // @todo: hardcoded codeset
        bind_textdomain_codeset($domain, 'UTF-8');

        return $this;
    }

    /**
     * translate
     *
     * <code>
     * // simple
     * // equal to gettext('Message')
     * Translator::translate('Message');
     *
     * // simple replace of one or more argument(s)
     * // equal to sprintf(gettext('Message to %s'), 'Username')
     * Translator::translate('Message to %s', 'Username');
     * </code>
     *
     * @param $message
     * @return string
     */
    public static function translate($message)
    {
        if (function_exists('gettext')) {
            $message = gettext($message);
        }

        if (func_num_args() > 1) {
            $args = array_slice(func_get_args(), 1);
            $message = vsprintf($message, $args);
        }

        return $message;
    }

    /**
     * translate plural form
     *
     * <code>
     * // plural form + sprintf
     * // equal to sprintf(ngettext('%d comment', '%d comments', 4), 4)
     * Translator::translatePlural('%d comment', '%d comments', 4, 4)
     *
     * // plural form + sprintf
     * // equal to sprintf(ngettext('%d comment', '%d comments', 4), 4, 'Topic')
     * Translator::translatePlural('%d comment to %s', '%d comments to %s', 4, 'Topic')
     * </code>
     * @see http://docs.translatehouse.org/projects/localization-guide/en/latest/l10n/pluralforms.html
     * @param $singular
     * @param $plural
     * @param $number
     * @return string
     */
    public static function translatePlural($singular, $plural, $number)
    {
        if (function_exists('ngettext')) {
            $message = ngettext($singular, $plural, $number);
        } else {
            $message = $singular;
        }

        if (func_num_args() > 3) {
            $args = array_slice(func_get_args(), 3);
            $message = vsprintf($message, $args);
        }

        return $message;
    }

    static function translit($str, $type = 'url')
    {
        switch ($type){
            case 'url':
                $str = self::translitBackToCyr($str);
                $str = self::translitEncodedURL($str);
                break;
            default:
                $str = self::_translit($str);
                break;
        }

        return $str;
    }

    static function translitEncodedURL($st){
        $lit = array(
            "%D0%B0" => "a",
            "%D0%B1" => "b",
            "%D0%B2" => "v",
            "%D0%B3" => "g",
            "%D0%B4" => "d",
            "%D0%B5" => "e",
            "%D1%91" => "jo",
            "%D0%B6" => "zh",
            "%D0%B7" => "z",
            "%D0%B8" => "i",
            "%D0%B9" => "ij",
            "%D0%BA" => "k",
            "%D0%BB" => "l",
            "%D0%BC" => "m",
            "%D0%BD" => "n",
            "%D0%BE" => "o",
            "%D0%BF" => "p",
            "%D1%80" => "r",
            "%D1%81" => "s",
            "%D1%82" => "t",
            "%D1%83" => "u",
            "%D1%84" => "f",
            "%D1%85" => "h",
            "%D1%86" => "z",
            "%D1%87" => "ch",
            "%D1%88" => "sh",
            "%D1%89" => "shch",
            "%D1%8A" => "",
            "%D1%8B" => "y",
            "%D1%8C" => "",
            "%D1%8D" => "e",
            "%D1%8E" => "ju",
            "%D1%8F" => "ja",
            "%20"    => " ",
            "%D0%90" => "A",
            "%D0%91" => "B",
            "%D0%92" => "V",
            "%D0%93" => "G",
            "%D0%94" => "D",
            "%D0%95" => "E",
            "%D0%81" => "Yo",
            "%D0%96" => "Zh",
            "%D0%97" => "Z",
            "%D0%98" => "I",
            "%D0%99" => "Ij",
            "%D0%9A" => "K",
            "%D0%9B" => "L",
            "%D0%9C" => "M",
            "%D0%9D" => "N",
            "%D0%9E" => "O",
            "%D0%9F" => "P",
            "%D0%A0" => "R",
            "%D0%A1" => "S",
            "%D0%A2" => "T",
            "%D0%A3" => "U",
            "%D0%A4" => "F",
            "%D0%A5" => "H",
            "%D0%A6" => "C",
            "%D0%A7" => "Ch",
            "%D0%A8" => "Sh",
            "%D0%A8" => "Sh",
            "%D0%A9" => "Sch",
            "%D0%AA" => "'",
            "%D0%AB" => "Y",
            "%D0%AC" => "'",
            "%D0%AD" => "E",
            "%D0%AE" => "Yu",
            "%D0%AF" => "Ya",
        );

        return $st = strtr($st, $lit);
    } // --

    static function translitBackToCyr($st){
        $lit = array(
            "%D0%B0" => "а",
            "%D0%B1" => "б",
            "%D0%B2" => "в",
            "%D0%B3" => "г",
            "%D0%B4" => "д",
            "%D0%B5" => "е",
            "%D1%91" => "ё",
            "%D0%B6" => "ж",
            "%D0%B7" => "з",
            "%D0%B8" => "и",
            "%D0%B9" => "й",
            "%D0%BA" => "к",
            "%D0%BB" => "л",
            "%D0%BC" => "м",
            "%D0%BD" => "н",
            "%D0%BE" => "о",
            "%D0%BF" => "п",
            "%D1%80" => "р",
            "%D1%81" => "с",
            "%D1%82" => "т",
            "%D1%83" => "у",
            "%D1%84" => "ф",
            "%D1%85" => "х",
            "%D1%86" => "ц",
            "%D1%87" => "ч",
            "%D1%88" => "ш",
            "%D1%89" => "щ",
            "%D1%8A" => "ъ",
            "%D1%8B" => "ы",
            "%D1%8C" => "ь",
            "%D1%8D" => "э",
            "%D1%8E" => "ю",
            "%D1%8F" => "я",

            "%D0%90" => "А",
            "%D0%91" => "Б",
            "%D0%92" => "В",
            "%D0%93" => "Г",
            "%D0%94" => "Д",
            "%D0%95" => "Е",
            "%D0%81" => "Ё",
            "%D0%96" => "Ж",
            "%D0%97" => "З",
            "%D0%98" => "И",
            "%D0%99" => "Й",
            "%D0%9A" => "К",
            "%D0%9B" => "Л",
            "%D0%9C" => "М",
            "%D0%9D" => "Н",
            "%D0%9E" => "О",
            "%D0%9F" => "П",
            "%D0%A0" => "Р",
            "%D0%A1" => "С",
            "%D0%A2" => "Т",
            "%D0%A3" => "У",
            "%D0%A4" => "Ф",
            "%D0%A5" => "Х",
            "%D0%A6" => "Ц",
            "%D0%A7" => "Ч",
            "%D0%A8" => "Ш",
            "%D0%A9" => "Щ",
            "%D0%AA" => "Ъ",
            "%D0%AB" => "Ы",
            "%D0%AC" => "Ь",
            "%D0%AD" => "Э",
            "%D0%AE" => "Ю",
            "%D0%AF" => "Я",

        );

        return $st = strtr($st, $lit);
    } // --

    static function _translit($st) {
        $lit = array(
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "yo",
            "ж" => "g",
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
            "ц" => "z",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "shch",
            "ъ" => "",
            "ы" => "y",
            "ь" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya",

            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Е" => "E",
            "Ё" => "Yo",
            "Ж" => "G",
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
            "Ц" => "Z",
            "Ч" => "Ch",
            "Ш" => "Sh",
            "Щ" => "Shch",
            "Ъ" => "",
            "Ы" => "Y",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "Yu",
            "Я" => "Ya",
        );
        return $st = strtr($st, $lit);
    } // --
}
