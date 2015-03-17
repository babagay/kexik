<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 *
 * [примеры]
 *      fb('detectBaseUrl: ' . $_this->getRequest()->detectBaseUrl()); //  /zoqa
        fb('getHttpHost: ' . $_this->getRequest()->getHttpHost());     //  127.0.0.1
        fb('getCleanUri: ' . $_this->getRequest()->getCleanUri());     // /zoqa/виртуальный/вопрос/7/кто_такой/question/3+3
        fb('getRequestUri: ' . $_this->getRequest()->getRequestUri()); // /zoqa/виртуальный/вопрос/7/кто_такой/question/3+3
        fb( $_this->getRequest()->getURI()); // Array(1=виртуальный, 2=вопрос ... )
        fb($_this->getRequest()->вопрос);    // 7
 */

/**
 * @namespace
 */
namespace Bluz\Request;
use Bluz\Translator\Translator;

/**
 * HTTP Request
 *
 * @category Bluz
 * @package  Request
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 16:59
 */
class HttpRequest extends AbstractRequest
{
    /**
     * @const string HTTP SCHEME constant names
     */
    const SCHEME_HTTP = 'http';
    const SCHEME_HTTPS = 'https';

    public $get = array();
    public $post = array();
    public $cookie = array();
    public $files = array();
    public $server = array();
    public $uri = array();

   // public $get_params = array();
    public $uri_params = array();

    /**
     * @var HttpFileUpload
     */
    protected $fileUpload;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->method = $this->getServer('REQUEST_METHOD');
        $request = file_get_contents('php://input'); //php://input is not available with enctype="multipart/form-data"

        // support header like "application/json" and "application/json; charset=utf-8"
        if (stristr($this->getHeader('Content-Type'), 'application/json')) {
            $data = (array) json_decode($request);
        } else {

            switch ($this->method) {
                case self::METHOD_POST:
                    $data = $_POST;
                    break;
                default:
                    parse_str($request, $data);
                    break;
            }
        }



        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;

        $this->uri = $this->getURI();

        $this->initGetParams();

        $this->setParams(array_merge($data, $this->uri_params));

    }

    /**
     * void
     */
    function initGetParams()
    {
        foreach($this->uri as $k =>  $param){
            if(isset($this->uri[$k+1])){
                $param = Translator::translit($param);
                $this->uri_params[$param] = Translator::translit($this->uri[$k+1]);
            }
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    function getParameter($name)
    {
        $value = null;

        foreach($this->uri as $k => $v){
            if($v == $name){
                if(isset($this->uri[$k+1])){
                    $value = $this->uri[$k+1];
                    break;
                }
            }
        }

        return $value;
    }

    /**
     * Access values contained in the superglobals as public members
     * Order of precedence: 1. GET, 2. POST, 3. COOKIE, 4. SERVER, 5. ENV
     *
     * @see http://msdn.microsoft.com/en-us/library/system.web.httprequest.item.aspx
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        switch (true) {
            case parent::__isset($key):
                return parent::__get($key);
            case isset($this->uri_params[$key]):
                return $this->uri_params[$key];
            case isset($_GET[$key]):
                return $_GET[$key];
            case isset($_POST[$key]):
                return $_POST[$key];
            case isset($_COOKIE[$key]):
                return $_COOKIE[$key];
            case isset($_SERVER[$key]):
                return $_SERVER[$key];
            case isset($_ENV[$key]):
                return $_ENV[$key];
            case isset($this->$key):
                return $this->$key;
            default:
                return null;
        }
    }

    /**
     * Check to see if a property is set
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        switch (true) {
            case parent::__isset($key):
                return true;
            case isset($_GET[$key]):
                return true;
            case isset($_POST[$key]):
                return true;
            case isset($_COOKIE[$key]):
                return true;
            case isset($_SERVER[$key]):
                return true;
            case isset($_ENV[$key]):
                return true;
            default:
                return false;
        }
    }

    /**
     * Unset custom param
     *
     * @param $key
     */
    public function __unset($key)
    {
        parent::__unset($key);

        if (isset($_GET[$key])) {
            unset($_GET[$key]);
        }
        if (isset($_POST[$key])) {
            unset($_POST[$key]);
        }
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
        }
        if (isset($_SERVER[$key])) {
            unset($_SERVER[$key]);
        }
        if (isset($_ENV[$key])) {
            unset($_ENV[$key]);
        }
    }

    /**
     * Get all request parameters
     *
     * @return array
     */
    public function getAllParams()
    {
        return array_merge($_POST, $_GET, $this->uri_params);
        //return array_merge($_POST, $_GET, $this->params);
    }

    /**
     * Get the request URI scheme
     *
     * @return string
     */
    public function getScheme()
    {
        return ($this->getServer('HTTPS') == 'on') ? self::SCHEME_HTTPS : self::SCHEME_HTTP;
    }


    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * Should work with Prototype/Script.aculo.us, possibly others.
     *
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return ($this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    /**
     * Is this a Flash request?
     *
     * @return boolean
     */
    public function isFlashRequest()
    {
        $header = strtolower($this->getHeader('USER_AGENT'));
        return (strstr($header, ' flash')) ? true : false;
    }

    /**
     * Return the value of the given HTTP header. Pass the header name as the
     * plain, HTTP-specified header name. Ex.: Ask for 'Accept' to get the
     * Accept header, 'Accept-Encoding' to get the Accept-Encoding header.
     *
     * @param string $header HTTP header name
     * @return string|boolean HTTP header value, or false if not found
     */
    public function getHeader($header)
    {
        // Try to get it from the $_SERVER array first
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (isset($_SERVER[$temp])) {
            return $_SERVER[$temp];
        }
        // This seems to be the only way to get the Authorization header on
        // Apache
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers[$header])) {
                return $headers[$header];
            }
            $header = strtolower($header);
            foreach ($headers as $key => $value) {
                if (strtolower($key) == $header) {
                    return $value;
                }
            }
        }

        return false;
    }

    /**
     * Retrieve a member of the $_GET super global
     *
     * If no $key is passed, returns the entire $_GET array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns null if key does not exist
     */
    public function getQuery($key = null, $default = null)
    {
        if (null === $key) {
            return $_GET;
        }

        return (isset($_GET[$key])) ? $_GET[$key] : $default;
    }

    /**
     * Is this a GET method request?
     *
     * @return bool
     */
    public function isGet()
    {
        return ($this->getMethod() === self::METHOD_GET);
    }

    /**
     * Is this a POST method request?
     *
     * @return bool
     */
    public function isPost()
    {
        return ($this->getMethod() === self::METHOD_POST);
    }

    /**
     * Is this a PUT method request?
     *
     * @return bool
     */
    public function isPut()
    {
        return ($this->getMethod() === self::METHOD_PUT);
    }

    /**
     * Is this a DELETE method request?
     *
     * @return bool
     */
    public function isDelete()
    {
        return ($this->getMethod() === self::METHOD_DELETE);
    }

    /**
     * Retrieve a member of the $_POST super global
     *
     * If no $key is passed, returns the entire $_POST array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns null if key does not exist
     */
    public function getPost($key = null, $default = null)
    {
        if (null === $key) {
            return $_POST;
        }

        return (isset($_POST[$key])) ? $_POST[$key] : $default;
    }

    /**
     * getFileUpload
     *
     * @return HttpFileUpload
     */
    public function getFileUpload()
    {
        if (!$this->fileUpload) {
            $this->fileUpload = new HttpFileUpload();
        }
        return $this->fileUpload;
    }

    /**
     * Retrieve a member of the $_COOKIE superglobal
     *
     * If no $key is passed, returns the entire $_COOKIE array.
     *
     * @todo How to retrieve from nested arrays
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns null if key does not exist
     */
    public function getCookie($key = null, $default = null)
    {
        if (null === $key) {
            return $_COOKIE;
        }

        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
    }

    /**
     * Retrieve a member of the $_SERVER superglobal
     *
     * If no $key is passed, returns the entire $_SERVER array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns null if key does not exist
     */
    public function getServer($key = null, $default = null)
    {
        if (null === $key) {
            return $_SERVER;
        }

        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

    /**
     * Get the HTTP host.
     *
     * "Host" ":" host [ ":" port ] ; Section 3.2.2
     * Note the HTTP Host header is not the same as the URI host.
     * It includes the port while the URI host doesn't.
     *
     * @return string
     */
    public function getHttpHost()
    {
        $host = $this->getServer('HTTP_HOST');
        if (!empty($host)) {
            return $host;
        }

        $scheme = $this->getScheme();
        $name = $this->getServer('SERVER_NAME');
        $port = $this->getServer('SERVER_PORT');

        if (null === $name) {
            return '';
        } elseif (($scheme == self::SCHEME_HTTP && $port == 80) || ($scheme == self::SCHEME_HTTPS && $port == 443)) {
            return $name;
        } else {
            return $name . ':' . $port;
        }
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function getRequestUri()
    {
        if ($this->requestUri === null) {
            $this->requestUri = $this->detectRequestUri();
        }
        return $this->requestUri;
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function getCleanUri()
    {
        if ($this->cleanUri === null) {
            $uri = parse_url($this->getRequestUri());
            $uri = $uri['path'];

            if ($this->getBaseUrl() && strpos($uri, $this->getBaseUrl()) === 0) {
                $uri = substr($uri, strlen($this->getBaseUrl()));
            }
            $this->cleanUri = $uri;
        }
        return $this->cleanUri;
    }

    /**
     * Get pure URI (without http://127.0.0.1/zoqa/ AND ?a=1)
     *
     * @return array|null|string
     */
    function getURI($uri = null){



        $self_url =  $this->selfURL();

        // $uri =  $this->clean($_SERVER['REQUEST_URI']);

        if($uri === null)
            $uri =  $this->clean( str_replace(PUBLIC_URL,'',$self_url ));

        // отсечь гет-парматеры
        $get = explode('?',$uri);

        if(isset($get[1]))
            $uri = $get[0];

        #
        $uri = explode("/",$uri);

        $tmp[] = null;

        if(is_array($uri))
            foreach($uri as $k => $v){
                if( (bool)$v !== false ) $tmp[] = $v;
            }

        unset($tmp[0]);

        $uri = $tmp;

        unset($tmp);

        return $uri;
    }

    /**
     * @param $data
     * @return array|string
     */
    public function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT);
        }

        return $data;
    }

    /**
     * Get the base URL. Like http://127.0.0.1/zoqa/
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if (null === $this->baseUrl) {
            $this->setBaseUrl($this->detectBaseUrl());
        }

        return $this->baseUrl;
    }

    /**
     * Get self URL like http://127.0.0.1/zoqa/
     *
     * @return string
     */
    function selfURL(){
        if(!isset($_SERVER['REQUEST_URI']))    $suri = $_SERVER['PHP_SELF'];
        else $suri = $_SERVER['REQUEST_URI'];
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $sp=strtolower($_SERVER["SERVER_PROTOCOL"]);
        $pr =    substr($sp,0,strpos($sp,"/")).$s;
        $pt = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);

        return $pr."://".$_SERVER['SERVER_NAME'].$pt.$suri;
    }

    /**
     * Get the client's IP address
     *
     * @param  boolean $checkProxy
     * @return string
     */
    public function getClientIp($checkProxy = true)
    {
        if ($checkProxy && $this->getServer('HTTP_CLIENT_IP') != null) {
            $ip = $this->getServer('HTTP_CLIENT_IP');
        } else {
            if ($checkProxy && $this->getServer('HTTP_X_FORWARDED_FOR') != null) {
                $ip = $this->getServer('HTTP_X_FORWARDED_FOR');
            } else {
                $ip = $this->getServer('REMOTE_ADDR');
            }
        }

        return $ip;
    }

    /**
     * Detect the base URI for the request
     *
     * Looks at a variety of criteria in order to attempt to autodetect a base
     * URI, including rewrite URIs, proxy URIs, etc.
     *
     * @return string
     */
    public function detectRequestUri()
    {
        $requestUri = null;

        // Check this first so IIS will catch.
        $httpXRewriteUrl = $this->getServer('HTTP_X_REWRITE_URL');
        if ($httpXRewriteUrl !== null) {
            $requestUri = $httpXRewriteUrl;
        }

        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        $iisUrlRewritten = $this->getServer('IIS_WasUrlRewritten');
        $unencodedUrl = $this->getServer('UNENCODED_URL', '');
        if ('1' == $iisUrlRewritten && '' !== $unencodedUrl) {
            return $unencodedUrl;
        }

        // HTTP proxy requests setup request URI with scheme and host
        // [and port] + the URL path, only use URL path.
        if($this->get(1) !== null){
            if (!$httpXRewriteUrl) {
                $requestUri = $this->getServer('REQUEST_URI');
            }
        }

        if ($requestUri !== null) {
            $schemeAndHttpHost = $this->getScheme() . '://' . $this->getServer('HTTP_HOST');

            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }
            return $requestUri;
        }

        // IIS 5.0, PHP as CGI.
        $origPathInfo = $this->getServer('ORIG_PATH_INFO');
        if ($origPathInfo !== null) {
            $queryString = $this->getServer('QUERY_STRING', '');
            if ($queryString !== '') {
                $origPathInfo .= '?' . $queryString;
            }
            return $origPathInfo;
        }

        return '/';
    }

    /**
     * Auto-detect the base path from the request environment
     *
     * Uses a variety of criteria in order to detect the base URL of the request
     * (i.e., anything additional to the document root).
     *
     * The base URL includes the schema, host, and port, in addition to the path.
     *
     * @return string
     */
    public function detectBaseUrl()
    {
        $filename = $this->getServer('SCRIPT_FILENAME', '');
        $scriptName = $this->getServer('SCRIPT_NAME');
        $phpSelf = $this->getServer('PHP_SELF');
        $origScriptName = $this->getServer('ORIG_SCRIPT_NAME');

        if ($scriptName !== null && basename($scriptName) === $filename) {
            $baseUrl = $scriptName;
        } elseif ($phpSelf !== null && basename($phpSelf) === $filename) {
            $baseUrl = $phpSelf;
        } elseif ($origScriptName !== null && basename($origScriptName) === $filename) {
            // 1and1 shared hosting compatibility.
            $baseUrl = $origScriptName;
        } else {
            // Backtrack up the SCRIPT_FILENAME to find the portion
            // matching PHP_SELF.
            $path = $phpSelf ? : '';
            $segments = array_reverse(explode('/', trim($filename, '/')));
            $index = 0;
            $last = count($segments);
            $baseUrl = '';

            do {
                $segment = $segments[$index];
                $baseUrl = '/' . $segment . $baseUrl;
                $index++;
            } while ($last > $index && false !== ($pos = strpos($path, $baseUrl)) && 0 !== $pos);
        }

        // Does the base URL have anything in common with the request URI?
        $requestUri = $this->getRequestUri();

        // Full base URL matches.
        if (0 === strpos($requestUri, $baseUrl)) {
            return $baseUrl;
        }

        // Directory portion of base path matches.
        if (0 === strpos($requestUri, dirname($baseUrl))) {
            $baseUrl = dirname($baseUrl);
            return $baseUrl;
        }

        $truncatedRequestUri = $requestUri;

        if (false !== ($pos = strpos($requestUri, '?'))) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);

        // No match whatsoever
        if (empty($basename) || false === strpos($truncatedRequestUri, $basename)) {
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of the base path. $pos !== 0 makes sure it is not matching a
        // value from PATH_INFO or QUERY_STRING.
        if (strlen($requestUri) >= strlen($baseUrl)
            && (false !== ($pos = strpos($requestUri, $baseUrl)) && $pos !== 0)
        ) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return $baseUrl;
    }

    /**
     * @param null $index
     * @return array|null|string
     */
    function getURIarg( $index = null  )
    {
        if(isset($index)){
            if(isset($this->uri[$index])){
                return Translator::translit($this->uri[$index]);
            }
            return null;
        } else
            return $this->uri;
    }

    /**
     * @param null $index
     * @return array|null|string
     */
    function get( $index = null ){
        return $this->getURIarg($index);
    }

    function getRawParams( $type = 'string' ){

        if($type == 'string'){
            if(isset($this->rawParams[0]))
                return $this->rawParams[0];
        }

        return null;
    }

}
