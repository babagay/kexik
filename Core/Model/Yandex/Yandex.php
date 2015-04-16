<?php
/**
 [приложение для Yandex API - фото] 
 ID: 7f3041fe0bf14bae935bcffab540f04e
Пароль: 561d7c1a26fc4396a127e36c8a8c4613
Callback URL: https://oauth.yandex.ru/verification_code

 [приложение для Yandex API - Паблик фото] 
 ID: f8d97173c0394aedaba85f45a4c5a4eb
Пароль: 6dd3122b52974d939b8f5ac75eaa773e
Callback URL: https://oauth.yandex.ru/verification_code
token: 30677997384945ecaedd5eb00ec318a9

[ссылки]
http://pyatnitsev.ru/tag/яндекс-фотки/
 *
 * [namespace]
 * Можно либо прописать здесь namespace, например, в соответствии с физическим путем namespace Core\Model\Yandex;,
 * при этом запись в lib\composer\autoload_classmap.php можно не добавлять (если пространство имен соответсвует пути);
 * либо убрать директиву namespace, но добавить запись в lib\composer\autoload_namespaces.php, тогда
 * вызов будет простым: $y = new Yandex();
*/

class Yandex {

    const CRLF = "\r\n";

    private $_url; // base yandex URL
    private $_api_photo_url;
    private $_oauth_url;
    private $_username;
    private $_password;
    private $_client_id;
    private $_client_secret;

    private $token;
    private $album;

    private $code;
    private $header_out;
    private $_response_header;
    private $_response_body;

    private $ch;
    private $curl;
    private $url = '';
    private $post = array();


    function __construct($config)
    {
        if(isset($config['base_url'])) $this->_url = $config['base_url'];
        if(isset($config['api_photo_url'])) $this->_api_photo_url = $config['api_photo_url'];
        if(isset($config['oauth_url'])) $this->_oauth_url = $config['oauth_url'];
        if(isset($config['username'])) $this->_username = $config['username'];
        if(isset($config['password'])) $this->_password = $config['password'];
        if(isset($config['client_id'])) $this->_client_id = $config['client_id'];
        if(isset($config['client_secret'])) $this->_client_secret = $config['client_secret'];
    }

    function getConfig()
    {
        return array(
            'base_url' => $this->_url,
            'api_photo_url' => $this->_api_photo_url,
            'oauth_url' => $this->_oauth_url,
            'username' => $this->_username,
            'password' => $this->_password,
            'client_id' => $this->_client_id,
            'client_secret' => $this->_client_secret,
        );
    }

    function setAlbum($name)
    {
        $this->album = $name;

        return $this;
    }

    function getToken($type = 'photo', $username = null, $password = null, $client_id = null, $client_secret = null)
    {
        // если токен есть в базе, взять оттуда
        // Если он создан менее года назад, использовать его, если более, обновить
        // взять число секунд в один год
        // Запросить базу, есть ли токен с датой позже минус года

        if( is_string($this->token) ) return $this->token;

        if($type == 'photo') $descriptor = "yandex_fotki_token";

        $db =  app()->getDb();
        $previous_year = time() - (12 * 30 * 24 * 60 * 60);
        $sql = " SELECT *
                 FROM config
                 WHERE descriptor = '$descriptor'
                 AND dateline > $previous_year
        ";
        $row = $db->fetchRow( $sql );

        if( $row ){
            $this->token = $row['value'];
            return $row['value'];
        }


        // Получить новый токен
        if(is_null($username)) $username = $this->_username;
        if(is_null($password)) $password = $this->_password;
        if(is_null($client_id)) $client_id = $this->_client_id;
        if(is_null($client_secret)) $client_secret = $this->_client_secret;

        //  $url = 'https://oauth.yandex.ru/token';
        // TODO если урл не задан кинуть ексепшн

        $url = $this->_oauth_url . 'token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 9);
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=password&username=$username&password=$password&client_id=$client_id&client_secret=$client_secret"); // add POST fields
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
        $Resp = json_decode($result, true);

        // TODO cохранить токен в базу
        $arr = array(
            "descriptor" => $descriptor,
            "value" => $Resp['access_token'],
            "description" => "Yandex API token",
            "dateline" => time()
        );
        $row = new Core\Model\Config\Row($arr);
        $row->save();

        $this->token = $Resp['access_token'];

        return $Resp['access_token'];
    }

    /*
    function _curlWrap( $url, $post = array(), $action = 'POST', $api = 'photo', $headers = array(), $params = null, $file = null, $size = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );

        curl_setopt($ch , CURLOPT_POST, true);
        curl_setopt($ch , CURLOPT_POSTFIELDS, $post);

        curl_setopt($ch , CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch , CURLOPT_HEADER, false);
        curl_setopt($ch , CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER, true);

        //
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // возвращать результат работы в переменную
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $output = curl_exec($ch);
        // $output = curl_multi_getcontent ($ch);

        $this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->header_out = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        //


        $headerSize   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseBody = substr($output, $headerSize);
        $this->_response_header = substr($output, 0, $headerSize);

        return $output;
    }
*/

    // Переделать метод под себя
    function curlWrap( $url, $json = '', $action = 'GET', $api = 'photo', $headers = null, $params = null, $file = null, $size = null)
    {
        $base_url = '';
        if($api == 'photo') $base_url = $this->_api_photo_url;

        $ch = curl_init();

        // FIXME - что выбрать?
        curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_URL, $base_url.$url); // set url to post to
        //curl_setopt($ch, CURLOPT_USERPWD, $base_url."/token:".ZDAPIKEY);

        //fb($base_url.$url);

        switch($action){
            case "POST":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                //curl_setopt($ch, CURLOPT_POSTFIELDS, "value=".urlencode($json));
                //curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

                break;
            case "GET":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "CUSTOM-POST":
                // curl_setopt($ch, CURLOPT_POSTFIELDS, '{"product":{"handle":"WU1202","id":392542783,"product_type":"Football Shirt","title":"Walter Football Shirt Kids"}}');
                // curl_setopt($ch, CURLOPT_POST, 1); // set POST method использовать метод POST
                // Используем либо эту опцию либо CURLOPT_CUSTOMREQUEST
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

                break;
            case 'CUSTOM-PUT':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                // Может надо сбилдить поля
                // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params) );
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // PUT не поддерживается






                if(!is_null($file)){
                    $fl       = fopen($file, 'r');
                    $fileData = fread($fl, $size);
                    // curl_setopt( $ch, CURLOPT_PUT, TRUE ); // отваливается
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $fileData);
                    curl_setopt( $ch, CURLOPT_INFILE, $fl );
                    curl_setopt( $ch, CURLOPT_INFILESIZE, $size );
                }

                break;
            default:
                break;
        }

        if(is_array($headers)){
            // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8', 'Expect:']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json',
                'Accept: application/json',
            ) );
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        //curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // возвращать результат работы в переменную
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $output = curl_exec($ch);
        // $output = curl_multi_getcontent ($ch);

        if(!$output) {
            //fb( curl_error( $ch ) );
        }

        $this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->header_out = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        //


        $headerSize   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseBody = substr($output, $headerSize);
        $this->_response_header = substr($output, 0, $headerSize);
        $this->_response_body = $responseBody;

        //

        curl_close($ch);

        $decoded = json_decode($output);

        return $decoded;
    }

    /*
    function _storePhoto($picture,$filename = 'test')
    { // Загрузчик, вариант 1
        $album        = $this->album;
        $user         = $this->_username;
        $picture_blob = $this->scaleImageFileToBlob( $picture ); // path to image
        $url          = "api/users/$user/album/$album/photos/";
        $token        = $this->getToken();

        $length = filesize($picture);
        $ext = substr(strrchr($picture, '.'), 1);
        $type = "image/$ext";

        $headers = array(
            "Content-Type: $type",
            "Content-Length: $length",
            "Authorization: OAuth $token",
        );

        $body = $picture_blob;
        $body = rawurlencode ($body);

        return $this->curlWrap($url, $body, 'CUSTOM-POST', 'photo', $headers);
    }

    function E_storePhoto($picture,$filename = 'test')
    { // * Загрузчик, вариант 2
        $album        = $this->album;
        $user         = $this->_username;
        $picture_blob = $this->scaleImageFileToBlob( $picture ); // path to image
        $url          = "api/users/$user/album/$album/photos/";
        $token        = $this->getToken();

        $length = filesize($picture);
        $ext = substr(strrchr($picture, '.'), 1);
        $type = "image/$ext";
        $boundary = "Asrf456BGe4h";

        $name = "AttachedFile2";

        $headers = array(
            "Content-Type:  multipart/form-data;  boundary=$boundary",
            "Content-Length: $length",
            "Authorization: OAuth $token",
        );

        $crlf = self::CRLF;

        $body = "
$crlf
--$boundary$crlf
Content-Disposition: form-data; name=\"$name\"; filename=\"$filename\"$crlf
Content-Type: $type$crlf$crlf

$picture_blob$crlf$crlf

--$boundary$crlf
Content-Disposition: form-data; name=\"title\"$crlf$crlf

Горы$crlf
--$boundary$crlf
Content-Disposition: form-data; name=\"description\"$crlf$crlf

Сан-Катальдо, Сицилия$crlf
--$boundary$crlf
Content-Disposition: form-data; name=\"tags\"$crlf$crlf

природа$crlf
--$boundary$crlf
Content-Disposition: form-data; name=\"tags\"$crlf$crlf

небо$crlf
--$boundary$crlf
Content-Disposition: form-data; name=\"album\"$crlf$crlf

urn:yandex:fotki:$user:album:$album$crlf
 ";
        //$body = rawurlencode ($body);

        //fb($body);return;

        return $this->curlWrap($url, $body, 'CUSTOM-POST', 'photo', $headers);
    }

    function storePhoto_3($picture,$filename = 'test')
    { /// Вариант 3
        $album        = $this->album;
        $user         = $this->_username;
        $picture_blob = $this->scaleImageFileToBlob( $picture ); // path to image
        $url          = "post/";
        $token        = $this->getToken();

        $length   = filesize( $picture );
        $ext      = substr( strrchr( $picture, '.' ), 1 );
        $type     = "image/$ext";
        //$boundary = "Asrf456BGe4h";

        $body = "";

        //$file = fopen($picture, 'r');

        $headers = array(
            "Content-Type: multipart/form-data; charset=utf-8; type=entry",
            //"Content-Type:  multipart/form-data",
            "Content-Length: $length",
            "Authorization: OAuth $token",
        );

        // http_build_query

        //$params["access_type"] = "public";
        //$params["disable_comments"] = "true";
        $params["album"] = $this->album;
        $params["title"] = htmlentities($filename,ENT_COMPAT,"UTF-8");
        $params["image"] = "@" . $picture;

        //$response = $this->_curlWrap($url, $params, 'POST', 'photo', $headers);
        $response = $this->curlWrap($url, $body, 'CUSTOM-POST', 'photo', $headers, $params);
        //$response = $this->curlWrap($url, $body, 'CUSTOM-POST', 'photo', $headers, $params, $picture, $length);

        fb($this->_response_header);
        fb($this->_response_body);
        fb($this->header_out);
        //fb($this->code);


        return $response;
    }
    */

    /**
     * Вариант 4
     *
     * @param        $picture
     * @return mixed
     */
    function storePhoto($picture )
    {
        $length   = filesize( $picture );
        $ext      = substr( strrchr( $picture, '.' ), 1 );
        $type     = "image/$ext";

        $token        = $this->getToken();

        $album        = $this->album;
        $user         = $this->_username;

        $filename = '';
        $title = '';

        $image_id = null;

        $match = null;
        if( preg_match('/(?:[\/a-z_\\\-])*(?:[\\|\/]{1})([a-zа-я0-9_\-]*\.[jpg|jpeg|png]*)$/i',$picture,$match) ){
            $filename = $match[1];
            $title = explode('.',$filename);
            $title = $title[0];
        }

        $headers = array(
            //"Content-Type: multipart/form-data; charset=utf-8; type=entry",
            "Content-Type:  multipart/form-data",
            //"Content-Length: $length",
            "Authorization: OAuth $token",
            //'Authorization: FimpToken realm="fotki.yandex.ru", token="ea40342ddd8fe8b980ebf8492ffb4936"',
            //"Expect: "
        );

        $post_fields["album"] = $this->album;
        $post_fields["title"] = htmlentities(ucfirst($title),ENT_COMPAT,"UTF-8");
        $post_fields["image"] = "@" . $picture;

        /*
        $post_fields_str = '';

        foreach($post_fields as $key => $val){
            $post_fields_str .= "$key=$val&";
        }

        if($post_fields_str != '') $post_fields_str = substr($post_fields_str,0,-1);
        */

        try
        {
            $params = array('url' => $this->_api_photo_url . "post/", // 'http://www.google.com',
                            'host' => '',
                            'header' => $headers,
                            'method' => 'POST', // 'POST','HEAD'
                            'referer' => '',
                            'cookie' => '',
                            'post_fields' => $post_fields, // 'var1=value&var2=value
                            'file' => $picture,
            );

            $curl_options = array(
                CURLOPT_TIMEOUT => 20,
            );

            $this->init($params,$curl_options);
            $result = $this->exec();
            if ($result['curl_error'])    throw new Exception($result['curl_error']);
            if ($result['http_code']!='200')    throw new Exception("HTTP Code = ".$result['http_code']);
            //if (!$result['body'])        throw new Exception("Body of file is empty");
        }
        catch (Exception $e)
        {
            $mess = $e->getMessage();
            fb("Error of curl: " . $mess);
        }


        if( is_array($result) ){
            if(isset($result['body'])){
                $image_id = explode('=',$result['body']);
                @$image_id = $image_id[1];
            }
        }

        if(!is_null($image_id)) return $image_id;

        return $result;
    }

   /*
    function __storePhoto($picture,$filename = 'test')
    { // Вариант 0
        $album = $this->album;
        $user = $this->_username;
        $picture_blob = $this->scaleImageFileToBlob($picture); // path to image
        $url = "api/users/$user/album/$album/photos/";
        $token = $this->getToken();

        $crlf = "%0D%0A";

        //TODO вытаскиваьб $filename из пути $picture

        $length = filesize($picture);
        $ext = substr(strrchr($picture, '.'), 1);
        $type = "image/$ext";

        $boundary = "frekgh738gGHUehfui33qqQ";

        $title = 'asd'; // TODO
        $description = 'description'; // TODO
        $tags = 'блог'; // TODO



        $headers = array(
            //"Content-Type: $type",
            "Content-Length: $length",
            "Content-Type: multipart/form-data;  boundary=$boundary",
            "Authorization: OAuth $token",
        );

        $body = "\n
--$boundary\n
Content-Disposition: form-data;  name=\"image\"; filename=\"$filename\"\n
Content-Type: $type\n\n

$picture_blob\n\n

--$boundary\n
Content-Disposition: form-data; name=\"title\"\n\n

$title\n
--$boundary\n
Content-Disposition: form-data; name=\"description\"\n\n

$description\n
--$boundary\n
Content-Disposition: form-data; name=\"tags\"

$tags
--$boundary\n
Content-Disposition: form-data; name=\"tags\"\n\n

$tags
--$boundary\n
Content-Disposition: form-data; name=\"album\"\n\n

urn:yandex:fotki:$user:album:$album";


        if( ($check = preg_match('//u', $body)) === false){
            // Это UTF
            // fb($check);
        }

        // fb(mb_internal_encoding()); // выдало ISO-8859-1
        // fb( mb_detect_encoding($body) );

        //return;

        //$json = json_encode($picture_blob);
        // $json = array(0 => $picture_blob);
        //$json = $picture_blob;
        $json = '';

        //$body = array("value" => $body);
        $body = http_build_query( array("value" => $body) );
        //$body = "value=" . urlencode($body);

        //fb($url); // api/users/babagai/album/451358/photos/

        // Альтернативный метод загрузки
        $url = "post/";

        $body = "\n\n
--$boundary$crlf
Content-Disposition: form-data;  name='image'; filename='island'
Content-Type: image/png$crlf$crlf

$picture_blob$crlf
--$boundary$crlf
Content-Disposition: form-data; name='title'$crlf$crlf

island
--$boundary$crlf
Content-Disposition: form-data; name='album'$crlf$crlf

451358";

        // $name = '';
        //$body = array("value" => $body);
        // $body = http_build_query($body,null,null,PHP_QUERY_RFC1738); // PHP_QUERY_RFC3986
        $body = rawurlencode ($body);
        // $body = urlencode ($body);
        //$body = mb_convert_encoding($name,"UTF-8");

        //file_put_contents( getcwd() . "/public/temp/tmp.txt",$body );

        return $this->curlWrap($url, $body, 'CUSTOM-POST', 'photo', $headers);
    }

    */

    function getServiceDocument()
    {

    }

    function getPhotoInfo($image_id)
    {
        $url = "api/users/{$this->_username}/photo/$image_id/";

        //return $this->curlWrap($url);

        $params = array('url' => $this->_api_photo_url . $url ,
                        'host' => '',
                        'header' => array("Accept: application/json"),
                        'method' => 'GET', // 'POST','HEAD'
                        'referer' => '',
                        'cookie' => '',
        );


        $this->init($params);

        $result = $this->exec();

        $body = '';
        if(isset($result['body'])){
            $body = json_decode($result['body']);
        }

        return  $body  ;

    }

    function scaleImageFileToBlob($file) {

        $source_pic = $file;
        $max_width = 200;
        $max_height = 200;

        list($width, $height, $image_type) = getimagesize($file);

        switch ($image_type)
        {
            case 1: $src = imagecreatefromgif($file); break;
            case 2: $src = imagecreatefromjpeg($file);  break;
            case 3: $src = imagecreatefrompng($file); break;
            default: return '';  break;
        }

        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;

        if( ($width <= $max_width) && ($height <= $max_height) ){
            $tn_width = $width;
            $tn_height = $height;
        }elseif (($x_ratio * $height) < $max_height){
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        }else{
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
        }

        $tmp = imagecreatetruecolor($tn_width,$tn_height);

        /* Check if this image is PNG or GIF, then set if Transparent*/
        if(($image_type == 1) OR ($image_type==3))
        {
            imagealphablending($tmp, false);
            imagesavealpha($tmp,true);
            $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
            imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
        }
        imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

        /*
         * imageXXX() only has two options, save as a file, or send to the browser.
         * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
         * So I start the output buffering, use imageXXX() to output the data stream to the browser,
         * get the contents of the stream, and use clean to silently discard the buffered contents.
         */
        ob_start();

        switch ($image_type)
        {
            case 1: imagegif($tmp); break;
            case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
            case 3: imagepng($tmp, NULL, 0); break; // no compression
            default: echo ''; break;
        }

        $final_image = ob_get_contents();

        ob_end_clean();

        return $final_image;
    }

    public function init(array $params,$curl_options = array())
    {
        $user_agent = 'Mozilla/5.0 (Windows; U;
            Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';

        $header = array(
            "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
            "Accept-Language: ru-ru,ru;q=0.7,en-us;q=0.5,en;q=0.3",
            "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
            "Keep-Alive: 300"
        );

        if (isset($params['header']) && is_array($params['header'])) $header = array_merge($header,$params['header']);//$header[]=$params['header'];
        if (isset($params['host']) && $params['host'])      $header[]="Host: ".$params['host'];

        $options = array(
            CURLOPT_POST => 0,
            CURLOPT_HEADER => 1,
            CURLOPT_URL => $params['url'],
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERAGENT => $user_agent,
            CURLOPT_VERBOSE => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        );

        if(isset($curl_options[CURLOPT_TIMEOUT])) $options[CURLOPT_TIMEOUT] = $curl_options[CURLOPT_TIMEOUT];

        $this->ch = curl_init();

        curl_setopt_array($this->ch, $options);

        if ($params['referer'])    @curl_setopt ($this -> ch , CURLOPT_REFERER, $params['referer'] );
        if ($params['cookie'])    @curl_setopt ($this -> ch , CURLOPT_COOKIE, $params['cookie']);
        if (isset($params['login']) & isset($params['password']))
            @curl_setopt($this -> ch , CURLOPT_USERPWD,$params['login'].':'.$params['password']);

        if ($params['method'] == "HEAD") @curl_setopt($this -> ch,CURLOPT_NOBODY,1);

        if($params['method'] == 'POST'){
            @curl_setopt( $this -> ch, CURLOPT_POST, true );
            @curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params['post_fields']);
        }


    }

    public function exec()
    {
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);
        $result = array( 'header' => '',
                         'body' => '',
                         'curl_error' => '',
                         'http_code' => '',
                         'last_url' => '');

        if ( $error != "" ) {
            $result['curl_error'] = $error;
            return $result;
        }

        $header_size = curl_getinfo($this->ch,CURLINFO_HEADER_SIZE);
        $result['header'] = substr($response, 0, $header_size);
        $result['body'] = substr( $response, $header_size );
        $result['http_code'] = curl_getinfo($this -> ch,CURLINFO_HTTP_CODE);
        $result['last_url'] = curl_getinfo($this -> ch,CURLINFO_EFFECTIVE_URL);

        return $result;
    }
}

    /*
        //$token = GetToken($username, $password, $client_id, $client_secret);

        // http://api-fotki.yandex.ru/api/users/babagai/albums/
        // http://fotki.yandex.ru/users/babagai

        // Создать альбом

           $url = 'http://api-fotki.yandex.ru/api/users/babagai/albums/';
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
           curl_setopt($ch, CURLOPT_FAILONERROR, 1);
           curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
           curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
           curl_setopt($ch, CURLOPT_TIMEOUT, 9);
           curl_setopt($ch, CURLOPT_POST, 1); // set POST method
           curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=password&username=$username&password=$password&client_id=$client_id&client_secret=$client_secret"); // add POST fields
           $result = curl_exec($ch); // run the whole process
           curl_close($ch);
*/

    // Test Curl start
    /*
    $dst=ImageCreateTrueColor ($width = 100, $height = 500);

    $ch = curl_init();
    $url = "http://api-fotki.yandex.ru/api/users/babagai/";
    $Header = array(
        //'Content-Type' => 'image/jpeg',
        //Content-Length: 772094
        'Authorization' => 'OAuth f8d97173c0394aedaba85f45a4c5a4eb'
    );
    $Post = array(
        'grant_type' => 'password',
        'username' => 'babagai',
        'password' => 'djbyk.,db2012',
        //'client_id' => 'client_id',
        'client_secret' => 'client_secret',
    );



    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 9); // 9 ms

    curl_setopt($ch, CURLOPT_HEADER, true); // читать заголовок

    curl_setopt($ch, CURLOPT_HTTPHEADER, $Header);

    //curl_setopt($ch, CURLOPT_NOBODY, 1); // читать ТОЛЬКО заголовок без тела

    curl_setopt($ch, CURLOPT_POST, 1); // set POST method
    curl_setopt($ch, CURLOPT_POSTFIELDS, $Post); // add POST fields

    $result = curl_exec($ch);
    curl_close($ch);
    $Resp = json_decode($result, true);


    fb( $Resp );






    $result = curl_exec($ch); // run the whole process
    curl_close($ch);
    $Resp = json_decode($result, true);
*/
    // test curl end
