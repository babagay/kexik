<?php
/**
 * @version 2016 march
 */

define('PUBLIC_PATH', realpath(dirname(__FILE__) ));

// Блокировать попытку зайти на сайт через корневой домен (напр, на admin.remontik.kh.ua  через http://babagay.ru/remontik.kh.ua/)
if( file_exists(PUBLIC_PATH . '/Core/loader.php') ) {    
    //require(PUBLIC_PATH . '/Engine/startup.php');
    require_once PUBLIC_PATH . '/Core/loader.php';
} else {
  header("/");
}

/**
 * Block iframe embedding for prevent security issues
 * @link https://developer.mozilla.org/en-US/docs/HTTP/X-Frame-Options
 */
header('X-Frame-Options: SAMEORIGIN');

// Error Handler
function errorDisplay() {

    if (!$e = error_get_last()) {
        return;
    }

    $mode = '';
    @$mode = getenv('ZOQA_ENV');

    if($mode == 'debug'){
        //fb($e);
        @ $message = '[type]: ' . $e['type'] . ' ' .
                     ' ' . $e['message'] . ' ' .
                      '[file]: ' . $e['file'] . ' ' .
                      '[line]: ' . $e['line'] . ' ' ;       
    
        errorLog($message);        
    }

    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }

    require_once 'error.php';
}

// Shutdown function for handle critical and other errors
register_shutdown_function('errorDisplay');

// Try to run application
try {
    /**
     * @var \Composer\Autoload\ClassLoader $loader
     * var \Core\Bootstrap $core
     * @see http://getcomposer.org/apidoc/master/Composer/Autoload/ClassLoader.html
     */
    //require PATH_VENDOR . '/autoload.php';

    require PATH_LIB . '/autoload.php';

    require_once PATH_CORE . '/Bootstrap.php';

    require_once PATH_CORE . '/Exception.php';

    // Environment
    $env = getenv('ZOQA_ENV') ?: 'production'; // testing

    $core = \Application\Bootstrap::getInstance();

    $core->init($env)
        ->process();
    $core->render();
    $core->finish(); 

} catch (Exception $e) {

    if( getenv('ZOQA_ENV') == "debug"){
        //fb('Exception in index.php: ' . $e->getMessage() );
    }

    if( (bool)getenv('ZOQA_LOG') === true){
        errorLog($e->getMessage() ."\n". $e->getTraceAsString() ."\n");
    }

    require_once 'error.php';
}


