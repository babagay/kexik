<?php
/*
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">

<meta name="google-translate-customization" content="5d055142b78b35e8-5f92455780d7ed4b-ge3e67351787d6204-a"></meta>
</head>

<body>

hello world!

<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


</body>

</html>
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
     * @see http://getcomposer.org/apidoc/master/Composer/Autoload/ClassLoader.html
     */
    //require PATH_VENDOR . '/autoload.php';
    
    // if( file_exists(PATH_LIB . '/autoload.php') )
        require PATH_LIB . '/autoload.php';
    

    // FIXME remove test line on production
    // test
    //$serializer = new \Zend\Serializer\Adapter\Json;
 
    // if( file_exists(PATH_CORE . '/Bootstrap.php') )  
    require_once PATH_CORE . '/Bootstrap.php';
    // if( file_exists(PATH_CORE . '/Exception.php') )
    require_once PATH_CORE . '/Exception.php';
    

    // Environment
    $env = getenv('ZOQA_ENV') ?: 'production'; // testing

    /**
     * @var \Core\Bootstrap $core
     */
    $core = \Application\Bootstrap::getInstance();
    //fb($core instanceof \Application\Bootstrap ); true
 
    // test
    //$a = new \Zoqa\Testspace\zxc();
    //$a->Z('index','mindex',array(1=>234));



    $core->init($env)
        ->process();
    $core->render();
    $core->finish(); 

} catch (Exception $e) {

    if( getenv('ZOQA_ENV') == "debug"){
        fb('Exception in index.php: ' . $e->getMessage() );
    }

    if( (bool)getenv('ZOQA_LOG') === true){
        errorLog($e->getMessage() ."\n". $e->getTraceAsString() ."\n");
    }

    require_once 'error.php';
}


