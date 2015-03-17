<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * Simple functions of framework
 * be careful with this way
 * @author   Anton Shevchuk
 * @created  07.09.12 11:29
 */
if (!function_exists('debug')) {
    /**
     * Debug variables
     *
     * @return void
     */
    function debug()
    {
        // check definition
        if (!defined('DEBUG') or !DEBUG) {
            return;
        }

        ini_set('xdebug.var_display_max_children', 512);

        if ('cli' == PHP_SAPI) {
            if (extension_loaded('xdebug')) {
                // try to enable CLI colors
                ini_set('xdebug.cli_color', 1);
                xdebug_print_function_stack();
            } else {
                debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            }
            var_dump(func_get_args());
        } else {
            echo '<div class="textleft clear"><pre>';
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            var_dump(func_get_args());
            echo '</pre></div>';
        }
    }
}

if (!function_exists('app')) {
    /**
     * Alias for call instance of Application
     *
     * @return \Bluz\Application\Application
     */
    function app()
    {
        if( !\Core\Helper\Registry::getInstance()->has('application') )
            \Core\Helper\Registry::getInstance()->application = \Bluz\Application\Application::getInstance();

        //return \Bluz\Application\Application::getInstance();
        return  \Core\Helper\Registry::getInstance()->application;
    }
}

if (!function_exists('esc')) {
    /**
     * Escape variable for use in View
     *
     * @param string $variable
     * @param int $flags
     * @return string
     */
    function esc($variable, $flags = ENT_HTML5)
    {
        return htmlentities($variable, $flags, "UTF-8");
    }
}


// @codingStandardsIgnoreStart
if (!function_exists('__')) {
    /**
     * translate
     *
     * <code>
     * // simple
     * // equal to gettext('Message')
     * __('Message');
     *
     * // simple replace of one or more argument(s)
     * // equal to sprintf(gettext('Message to %s'), 'Username')
     * __('Message to %s', 'Username');
     * </code>
     *
     * @param $message
     * @return mixed
     */
    function __($message)
    {
        return call_user_func_array(array('\Bluz\Translator\Translator', 'translate'), func_get_args());
    }
}

if (!function_exists('_n')) {
    /**
     * translate plural form
     *
     * <code>
     *
     * // plural form + sprintf
     * // equal to sprintf(ngettext('%d comment', '%d comments', 4), 4)
     * _n('%d comment', '%d comments', 4, 4)
     *
     * // plural form + sprintf
     * // equal to sprintf(ngettext('%d comment', '%d comments', 4), 4, 'Topic')
     * _n('%d comment to %s', '%d comments to %s', 4, 'Topic')
     * </code>
     *
     * @param $singular
     * @param $plural
     * @param $number
     * @return mixed
     */
    function _n($singular, $plural, $number)
    {
        return call_user_func_array(array('\Bluz\Translator\Translator', 'translatePlural'), func_get_args());
    }
}
// @codingStandardsIgnoreEnd

/**
 * debug_string_backtrace() wrapper
 *
 * @param string $metka
 */
function dsb($metka = 'metka'){

    $trace = debug_string_backtrace($metka);
    asdr($trace);

}

/**
 * @param string $metka
 * @return mixed|string
 */
function debug_string_backtrace($metka = 'metka') {
    ob_start();

    echo "[".$metka."]\n";

    debug_print_backtrace();
    $trace = ob_get_contents();
    ob_end_clean();

    // Remove first item from backtrace as it's this function which
    // is redundant.
    $trace = preg_replace ('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);

    // Renumber backtrace items.
    $trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);

    return $trace;
}

function asd($var){
    ob_start();           
    print_r(  $var );
    echo '<p>'.'------------------'.'</p>';
    var_dump($var);
    $a = ob_get_clean(); 
    file_put_contents(PUBLIC_PATH . '/data/files/' . 'console.log.html' /*. rand()*/, $a);
} 

/**
 * @param $var
 * @param null $metka
 */
function asdr($var, $metka = null){
    $num = 100;
    @$num = file_get_contents(PUBLIC_PATH . "/" .  'test_log_counter.txt', true);
    $num = (int)$num;
    $num++;

    ob_start();
    if(isset($metka)) echo "[$metka]";
    print_r(  $var );
    echo '<p>'.'------------------'.'</p>';
    var_dump($var);
    $a = ob_get_clean();
    file_put_contents(PUBLIC_PATH . '/data/files/' . 'console.log.'.$num.'.html' , $a);
    file_put_contents(PUBLIC_PATH . '/test_log_counter.txt' , $num);
}

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code'))
{
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if($newcode !== NULL)
        {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }
        return $code;
    }
}