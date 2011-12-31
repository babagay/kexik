<?php
// Root path, double level up
$root = realpath(dirname(dirname(__FILE__)));
define('PATH_ROOT', $root);
define('PATH_CORE', $root . '/Core');
define('PATH_VIEW', $root . '/Core/View');
define('PATH_DATA', $root . '/data');
define('PATH_VENDOR', $root . '/vendor');
define('PATH_ZOQA', $root . '/vendor/Zoqa');
define('PATH_PUBLIC', $root . '/public');
define('PATH_LIB', $root . '/lib');
define('PATH_CACHE', $root . '/data/cache');

define('PATH_APPLICATION', $root );

define('TABLE_PREFIX', '' );
//define('TABLE_PREFIX', 'zoqa' );

// Define separators
  define('DS', '/');//DIRECTORY_SEPARATOR
  define('PS', PATH_SEPARATOR);
  define('PROTOCOL_TYPE', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http'));
  define('PUBLIC_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != 80 ? ":{$_SERVER['SERVER_PORT']}" : '') . rtrim(dirname($_SERVER['SCRIPT_NAME']), DS));

  define('CURRENT_URL', PROTOCOL_TYPE . "://" . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != 80 ? ":{$_SERVER['SERVER_PORT']}" : '') . rtrim(dirname($_SERVER['SCRIPT_NAME']), DS));

  define('PATH_LOGS', $root . "/data/log" );



// Debug mode for development environment only
if (getenv('ZOQA_DEBUG')) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}

//
// Write message to log file
//TODO проверить

if (!function_exists('errorLog')) {
    function errorLog($message)
    {
        if (getenv('ZOQA_LOG')
            && is_dir(PATH_DATA .'/log')
            && is_writable(PATH_DATA .'/log')) {
            file_put_contents(
                PATH_DATA .'/log/'.(date('Y-m-d')).'.log',
                PHP_EOL . "[".date("H:i:s")."]\t".$message,
                FILE_APPEND | LOCK_EX
            );
        }
    }
}

// Error Handler
if (!function_exists('errorHandler')) {
    function errorHandler()
    {
        $e = error_get_last();
        // check error type
        if (!is_array($e)
            || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
            return;
        }
        // clean all buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        // try to write log
        errorLog($e['message'] ."\n". $e['file'] ."#". $e['line'] ."\n");
    }
}

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');

require PUBLIC_PATH . "/Core/Autoloader.php";


//$autoloader = new Autoloader();

//$a = new Psr4AutoloaderClass();
//$a->register();

//$loader = new \Example\asz();
//$loader->register();




