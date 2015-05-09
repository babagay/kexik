<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */
/*
 * [URL]
 * http://127.0.0.1/zoqa/my/question/2+2
 *
 * my       - 1 аргумент - модуль. Реальный или виртуальный
 * question - 2 аргумент - точка входа. Она вычисляется по некоторым правилам.
 *              Например, название-точки
 *                          нужно разбить на слова,
 *                          сделать первые буквы Прописными
 *                          добавить слово action.
 *                          Получится actionНазваниеТочки()
 *
 *                          Если не указана точка, есть actionBase()
 *
 *  FIXME подключать классы типа Acl/Acl.php динамически в автолоадере, а не здесь вручную
 */



/**
 * files in this list is core of framework
 * use require_once it's really faster than use Loader for it
 *
 * @author   Anton Shevchuk
 * @created  15.07.11 13:21
 */

// add current directory to include path
set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());

// тест пространства имен
//$a = new \Core\Helper\Asd();
//$s = new \Zoqa\Testspace\zxc();

// exceptions
require_once 'Common/Exception.php';


// traits
require_once 'Common/Helper.php';
require_once 'Common/Singleton.php';
require_once 'Common/Options.php';

require_once PATH_LIB . '/twig/twig/lib/Twig/Autoloader.php';


// application
// require_once 'Application/Application.php';
require_once 'Application/Exception/ApplicationException.php';
//require_once 'View/ViewException.php';

// packages and support
//require_once 'Config/Config.php';
//require_once 'Config/ConfigException.php';
require_once 'EventManager/Event.php';
require_once 'EventManager/EventManager.php';
require_once 'Common/Nil.php';
require_once 'Messages/Messages.php';
require_once 'Request/AbstractRequest.php';
require_once 'Router/Router.php';
require_once 'Session/Session.php';
require_once 'Session/Store/AbstractStore.php';
require_once 'Session/Store/SessionStore.php';
//require_once 'Session/SessionException.php';
//require_once 'Translator/Translator.php';
//require_once 'View/ViewInterface.php';
//require_once 'View/View.php';
//require_once 'View/Layout.php';
//require_once 'Db/Db.php';
require_once 'Request/HttpRequest.php';
require_once 'Logger/Logger.php';
require_once 'Auth/Auth.php';
require_once 'Acl/Acl.php';
require_once 'Acl/AclException.php';
//require_once 'Grid/Grid.php';
//require_once 'Grid/Source/AbstractSource.php';
//require_once 'Grid/Source/ArraySource.php';
//require_once PATH_LIB.'/PHPMailer/PHPMailerAutoload.php';

require_once PATH_LIB.'/FirePHP/firephp-core/lib/FirePHPCore/FirePHP.class.php';

