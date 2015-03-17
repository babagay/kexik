<?php

namespace Core\Model\Vk;


class Vk {

    const VK_COOKIE_EXPIRE = 31556926; // 1 year
    const VK_COOKIE_NAME = 'vk';
    const SIGNED_REQUEST_ALGORITHM = 'HMAC-SHA256';

    /**
     * Indicates if we trust HTTP_X_FORWARDED_* headers.
     *
     * @var boolean
     */
    protected $trustForwarded = false;
    /**
     * Indicates if the CURL based @ syntax for file uploads is enabled.
     *
     * @var boolean
     */
    protected $fileUploadSupport = false;
    /**
     * The Application App Secret.
     *
     * @var string
     */
    protected $appSecret;
    /**
     * The Application ID.
     *
     * @var string
     */
    protected $appId;

    protected $persistent_data = array();

    // Stores the shared session ID if one is set.
    protected $sharedSessionID;

        function __construct($config = array()){
            if (!session_id()) {
                session_start();
            }

            $this->setAppId($config['appId']);
            $this->setAppSecret($config['secret']);
            if (isset($config['fileUpload'])) {
                $this->setFileUploadSupport($config['fileUpload']);
            }
            if (isset($config['trustForwarded']) && $config['trustForwarded']) {
                $this->trustForwarded = true;
            }
            $state = $this->getPersistentData('state');
            if (!empty($state)) {
                $this->state = $state;
            }

            if (!empty($config['sharedSession'])) {
                $this->initSharedSession();
            }
        }

    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
        return $this;
    }
    public function setFileUploadSupport($fileUploadSupport)
    {
        $this->fileUploadSupport = $fileUploadSupport;
        return $this;
    }
    protected function initSharedSession()
    {
        $cookie_name = $this->getSharedSessionCookieName();
        if (isset($_COOKIE[$cookie_name])) {
            $data = $this->parseSignedRequest($_COOKIE[$cookie_name]);
            if ($data && !empty($data['domain']) &&
                self::isAllowedDomain($this->getHttpHost(), $data['domain'])
            ) {
                // good case
                $this->sharedSessionID = $data['id'];
                return;
            }
            // ignoring potentially unreachable data
        }
        // evil/corrupt/missing case
        $base_domain = $this->getBaseDomain();
        $this->sharedSessionID = md5(uniqid(mt_rand(), true));
        $cookie_value = $this->makeSignedRequest(
            array(
                'domain' => $base_domain,
                'id' => $this->sharedSessionID,
            )
        );
        $_COOKIE[$cookie_name] = $cookie_value;
        if (!headers_sent()) {
            $expire = time() + self::VK_COOKIE_EXPIRE;
            setcookie($cookie_name, $cookie_value, $expire, '/', '.' . $base_domain);
        } else {
            // @codeCoverageIgnoreStart
            self::errorLog(
                'Shared session ID cookie could not be set! You must ensure you ' .
                'create the Facebook instance before headers have been sent. This ' .
                'will cause authentication issues after the first request.'
            );
            // @codeCoverageIgnoreEnd
        }
    }

    protected function getSharedSessionCookieName()
    {
        return self::VK_COOKIE_NAME . '_' . $this->getAppId();
    }

    /**
     * Each of the following four methods should be overridden in
     * a concrete subclass, as they are in the provided Facebook class.
     * The Facebook class uses PHP sessions to provide a primitive
     * persistent store, but another subclass--one that you implement--
     * might use a database, memcache, or an in-memory cache.
     *
     * @see Facebook
     */

    /**
     * Stores the given ($key, $value) pair, so that future calls to
     * getPersistentData($key) return $value. This call may be in another request.
     *
     * @param string $key
     * @param array $value
     *
     * @return void
     */
     protected function setPersistentData($key, $value){
         $this->persistent_data[$key] = $value;
     }

    /**
     * Get the data for $key, persisted by BaseFacebook::setPersistentData()
     *
     * @param string $key The key of the data to retrieve
     * @param boolean $default The default value to return if $key is not found
     *
     * @return mixed
     */
     protected function getPersistentData($key, $default = false){
         if(isset($this->persistent_data[$key]))
            return $this->persistent_data[$key];
         else
             return null;
     }

    /**
     * Clear the data with $key from the persistent storage
     *
     * @param string $key
     * @return void
     */
     protected function clearPersistentData($key){
         unset($this->persistent_data[$key]);
     }

    /**
     * Clear all data from the persistent storage
     *
     * @return void
     */
    protected function clearAllPersistentData(){
        $this->persistent_data = array();
    }



    /**
     * Base64 encoding that doesn't need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *   No padded =
     *
     * @param string $input base64UrlEncoded string
     * @return string
     */
    protected static function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Base64 encoding that doesn't need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *
     * @param string $input string
     * @return string base64Url encoded string
     */
    protected static function base64UrlEncode($input)
    {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);
        return $str;
    }

    /**
     * Destroy the current session
     */
    public function destroySession()
    {
        $this->accessToken = null;
        $this->signedRequest = null;
        $this->user = null;
        $this->clearAllPersistentData();

        // Javascript sets a cookie that will be used in getSignedRequest that we
        // need to clear if we can
        $cookie_name = $this->getSignedRequestCookieName();
        if (array_key_exists($cookie_name, $_COOKIE)) {
            unset($_COOKIE[$cookie_name]);
            if (!headers_sent()) {
                $base_domain = $this->getBaseDomain();
                setcookie($cookie_name, '', 1, '/', '.' . $base_domain);
            } else {
                // @codeCoverageIgnoreStart
                self::errorLog(
                    'There exists a cookie that we wanted to clear that we couldn\'t ' .
                    'clear because headers was already sent. Make sure to do the first ' .
                    'API call before outputing anything.'
                );
                // @codeCoverageIgnoreEnd
            }
        }
    }

    /**
     * Parses the metadata cookie that our Javascript API set
     *
     * @return  an array mapping key to value
     */
    protected function getMetadataCookie()
    {
        $cookie_name = $this->getMetadataCookieName();
        if (!array_key_exists($cookie_name, $_COOKIE)) {
            return array();
        }

        // The cookie value can be wrapped in "-characters so remove them
        $cookie_value = trim($_COOKIE[$cookie_name], '"');

        if (empty($cookie_value)) {
            return array();
        }

        $parts = explode('&', $cookie_value);
        $metadata = array();
        foreach ($parts as $part) {
            $pair = explode('=', $part, 2);
            if (!empty($pair[0])) {
                $metadata[urldecode($pair[0])] =
                    (count($pair) > 1) ? urldecode($pair[1]) : '';
            }
        }

        return $metadata;
    }

    protected static function isAllowedDomain($big, $small)
    {
        if ($big === $small) {
            return true;
        }
        return self::endsWith($big, '.' . $small);
    }

    protected static function endsWith($big, $small)
    {
        $len = strlen($small);
        if ($len === 0) {
            return true;
        }
        return substr($big, -$len) === $small;
    }

    /**
     * Prints to the error log if you aren't in command line mode.
     *
     * @param string $msg Log message
     */
    protected static function errorLog($msg)
    {
        // disable error log if we are running in a CLI environment
        // @codeCoverageIgnoreStart
        if (php_sapi_name() != 'cli') {
            error_log($msg);
        }
        // uncomment this if you want to see the errors on the page
        // print 'error_log: '.$msg."\n";
        // @codeCoverageIgnoreEnd
    }

    /**
     * Returns the Current URL, stripping it of known FB parameters that should
     * not persist.
     *
     * @return string The current URL
     */
    protected function getCurrentUrl()
    {
        $protocol = $this->getHttpProtocol() . '://';
        $host = $this->getHttpHost();
        $currentUrl = $protocol . $host . $_SERVER['REQUEST_URI'];
        $parts = parse_url($currentUrl);

        $query = '';
        if (!empty($parts['query'])) {
            // drop known fb params
            $params = explode('&', $parts['query']);
            $retained_params = array();
            foreach ($params as $param) {
                if ($this->shouldRetainParam($param)) {
                    $retained_params[] = $param;
                }
            }

            if (!empty($retained_params)) {
                $query = '?' . implode($retained_params, '&');
            }
        }

        // use port if non default
        $port =
            isset($parts['port']) &&
            (($protocol === 'http://' && $parts['port'] !== 80) ||
                ($protocol === 'https://' && $parts['port'] !== 443))
                ? ':' . $parts['port'] : '';

        // rebuild
        return $protocol . $parts['host'] . $port . $parts['path'] . $query;
    }

    /**
     * Returns true if and only if the key or key/value pair should
     * be retained as part of the query string.  This amounts to
     * a brute-force search of the very small list of Facebook-specific
     * params that should be stripped out.
     *
     * @param string $param A key or key/value pair within a URL's query (e.g.
     *                     'foo=a', 'foo=', or 'foo'.
     *
     * @return boolean
     */
    protected function shouldRetainParam($param)
    {
        foreach (self::$DROP_QUERY_PARAMS as $drop_query_param) {
            if (strpos($param, $drop_query_param . '=') === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Make a OAuth Request.
     *
     * @param string $url The path (required)
     * @param array $params The query/post data
     *
     * @return string The decoded response object
     * @throws FacebookApiException
     */
    protected function oauthRequest($url, $params)
    {
        if (!isset($params['access_token'])) {
            $params['access_token'] = $this->getAccessToken();
        }

        if (isset($params['access_token'])) {
            $params['appsecret_proof'] = $this->getAppSecretProof($params['access_token']);
        }

        // json_encode all params values that are not strings
        foreach ($params as $key => $value) {
            if (!is_string($value)) {
                $params[$key] = json_encode($value);
            }
        }

        return $this->makeRequest($url, $params);
    }

    /**
     * Generate a proof of App Secret
     * This is required for all API calls originating from a server
     * It is a sha256 hash of the access_token made using the app secret
     *
     * @param string $access_token The access_token to be hashed (required)
     *
     * @return string The sha256 hash of the access_token
     */
    protected function getAppSecretProof($access_token)
    {
        return hash_hmac('sha256', $access_token, $this->getAppSecret());
    }

    /**
     * Makes an HTTP request. This method can be overridden by subclasses if
     * developers want to do fancier things or use something other than curl to
     * make the request.
     *
     * @param string $url The URL to make the request to
     * @param array $params The parameters to use for the POST body
     * @param \CurlHandler $ch Initialized curl handle
     *
     * @throws FacebookApiException
     * @return string The response text
     */
    protected function makeRequest($url, $params, $ch = null)
    {
        if (!$ch) {
            $ch = curl_init();
        }

        $opts = self::$CURL_OPTS;
        if ($this->getFileUploadSupport()) {
            $opts[CURLOPT_POSTFIELDS] = $params;
        } else {
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
        }
        $opts[CURLOPT_URL] = $url;

        // disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
        // for 2 seconds if the server does not support this header.
        if (isset($opts[CURLOPT_HTTPHEADER])) {
            $existing_headers = $opts[CURLOPT_HTTPHEADER];
            $existing_headers[] = 'Expect:';
            $opts[CURLOPT_HTTPHEADER] = $existing_headers;
        } else {
            $opts[CURLOPT_HTTPHEADER] = array('Expect:');
        }

        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);

        if (curl_errno($ch) == 60) { // CURLE_SSL_CACERT
            self::errorLog(
                'Invalid or no certificate authority found, ' .
                'using bundled information'
            );
            curl_setopt(
                $ch,
                CURLOPT_CAINFO,
                dirname(__FILE__) . '/fb_ca_chain_bundle.crt'
            );
            $result = curl_exec($ch);
        }

        // With dual stacked DNS responses, it's possible for a server to
        // have IPv6 enabled but not have IPv6 connectivity.  If this is
        // the case, curl will try IPv4 first and if that fails, then it will
        // fall back to IPv6 and the error EHOSTUNREACH is returned by the
        // operating system.
        if ($result === false && empty($opts[CURLOPT_IPRESOLVE])) {
            $matches = array();
            $regex = '/Failed to connect to ([^:].*): Network is unreachable/';
            if (preg_match($regex, curl_error($ch), $matches)) {
                if (strlen(@inet_pton($matches[1])) === 16) {
                    self::errorLog(
                        'Invalid IPv6 configuration on server, ' .
                        'Please disable or get native IPv6 on your server.'
                    );
                    self::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
                    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                    $result = curl_exec($ch);
                }
            }
        }

        if ($result === false) {
            $e = new FacebookApiException(
                array(
                    'error_code' => curl_errno($ch),
                    'error' => array(
                        'message' => curl_error($ch),
                        'type' => 'CurlException',
                    ),
                )
            );
            curl_close($ch);
            /** @var $e FacebookApiException */
            throw $e;
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Parses a signed_request and validates the signature.
     *
     * @param string $signed_request A signed token
     * @return array The payload inside it or null if the sig is wrong
     */
    protected function parseSignedRequest($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = self::base64UrlDecode($encoded_sig);
        $data = json_decode(self::base64UrlDecode($payload), true);

        if (strtoupper($data['algorithm']) !== self::SIGNED_REQUEST_ALGORITHM) {
            self::errorLog(
                'Unknown algorithm. Expected ' . self::SIGNED_REQUEST_ALGORITHM
            );
            return null;
        }

        // check sig
        $expected_sig = hash_hmac(
            'sha256',
            $payload,
            $this->getAppSecret(),
            $raw = true
        );
        if ($sig !== $expected_sig) {
            self::errorLog('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    /**
     * Makes a signed_request blob using the given data.
     *
     * @param array The data array.
     * @return string The signed request.
     */
    protected function makeSignedRequest($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException(
                'makeSignedRequest expects an array. Got: ' . print_r($data, true)
            );
        }
        $data['algorithm'] = self::SIGNED_REQUEST_ALGORITHM;
        $data['issued_at'] = time();
        $json = json_encode($data);
        $b64 = self::base64UrlEncode($json);
        $raw_sig = hash_hmac('sha256', $b64, $this->getAppSecret(), $raw = true);
        $sig = self::base64UrlEncode($raw_sig);
        return $sig . '.' . $b64;
    }

    /**
     * Build the URL for api given parameters.
     *
     * @param $method String the method name.
     * @return string The URL for the given parameters
     */
    protected function getApiUrl($method)
    {
        static $READ_ONLY_CALLS =
        array(
            'admin.getallocation' => 1,
            'admin.getappproperties' => 1,
            'admin.getbannedusers' => 1,
            'admin.getlivestreamvialink' => 1,
            'admin.getmetrics' => 1,
            'admin.getrestrictioninfo' => 1,
            'application.getpublicinfo' => 1,
            'auth.getapppublickey' => 1,
            'auth.getsession' => 1,
            'auth.getsignedpublicsessiondata' => 1,
            'comments.get' => 1,
            'connect.getunconnectedfriendscount' => 1,
            'dashboard.getactivity' => 1,
            'dashboard.getcount' => 1,
            'dashboard.getglobalnews' => 1,
            'dashboard.getnews' => 1,
            'dashboard.multigetcount' => 1,
            'dashboard.multigetnews' => 1,
            'data.getcookies' => 1,
            'events.get' => 1,
            'events.getmembers' => 1,
            'fbml.getcustomtags' => 1,
            'feed.getappfriendstories' => 1,
            'feed.getregisteredtemplatebundlebyid' => 1,
            'feed.getregisteredtemplatebundles' => 1,
            'fql.multiquery' => 1,
            'fql.query' => 1,
            'friends.arefriends' => 1,
            'friends.get' => 1,
            'friends.getappusers' => 1,
            'friends.getlists' => 1,
            'friends.getmutualfriends' => 1,
            'gifts.get' => 1,
            'groups.get' => 1,
            'groups.getmembers' => 1,
            'intl.gettranslations' => 1,
            'links.get' => 1,
            'notes.get' => 1,
            'notifications.get' => 1,
            'pages.getinfo' => 1,
            'pages.isadmin' => 1,
            'pages.isappadded' => 1,
            'pages.isfan' => 1,
            'permissions.checkavailableapiaccess' => 1,
            'permissions.checkgrantedapiaccess' => 1,
            'photos.get' => 1,
            'photos.getalbums' => 1,
            'photos.gettags' => 1,
            'profile.getinfo' => 1,
            'profile.getinfooptions' => 1,
            'stream.get' => 1,
            'stream.getcomments' => 1,
            'stream.getfilters' => 1,
            'users.getinfo' => 1,
            'users.getloggedinuser' => 1,
            'users.getstandardinfo' => 1,
            'users.hasapppermission' => 1,
            'users.isappuser' => 1,
            'users.isverified' => 1,
            'video.getuploadlimits' => 1
        );
        $name = 'api';
        if (isset($READ_ONLY_CALLS[strtolower($method)])) {
            $name = 'api_read';
        } elseif (strtolower($method) == 'video.upload') {
            $name = 'api_video';
        }
        return self::getUrl($name, 'restserver.php');
    }

    /**
     * Build the URL for given domain alias, path and parameters.
     *
     * @param $name string The name of the domain
     * @param $path string Optional path (without a leading slash)
     * @param $params array Optional query parameters
     *
     * @return string The URL for the given parameters
     */
    protected function getUrl($name, $path = '', $params = array())
    {
        $url = self::$DOMAIN_MAP[$name];
        if ($path) {
            if ($path[0] === '/') {
                $path = substr($path, 1);
            }
            $url .= $path;
        }
        if ($params) {
            $url .= '?' . http_build_query($params, null, '&');
        }

        return $url;
    }

    protected function getHttpHost()
    {
        if ($this->trustForwarded && isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            return $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        return $_SERVER['HTTP_HOST'];
    }

    protected function getHttpProtocol()
    {
        if ($this->trustForwarded && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                return 'https';
            }
            return 'http';
        }
        /*apache + variants specific way of checking for https*/
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)
        ) {
            return 'https';
        }
        /*nginx way of checking for https*/
        if (isset($_SERVER['SERVER_PORT']) &&
            ($_SERVER['SERVER_PORT'] === '443')
        ) {
            return 'https';
        }
        return 'http';
    }

    /**
     * Get the base domain used for the cookie.
     */
    protected function getBaseDomain()
    {
        // The base domain is stored in the metadata cookie if not we fallback
        // to the current hostname
        $metadata = $this->getMetadataCookie();
        if (array_key_exists('base_domain', $metadata) &&
            !empty($metadata['base_domain'])
        ) {
            return trim($metadata['base_domain'], '.');
        }
        return $this->getHttpHost();
    }

    /**
     * Get the App Secret.
     *
     * @return string the App Secret
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * Get the Application ID.
     *
     * @return string the Application ID
     */
    public function getAppId()
    {
        return $this->appId;
    }

}