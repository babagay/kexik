<?php
/*
 * URL для вызова: http://127.0.0.1/zoqa/my/base/question/2+2
 *
 * TODO Рассмотреть
 * - как сделать возможность вызова метода по-зендовски (через имя точки входа)
 * - попробовать взять параметры из урла прямо здесь (в замыкании)
 *
 * - прикрутить шаблонизатор
 *
 * [пример аякс]
 * var basePath = 'http://127.0.0.1/zoqa/'
	$.post(basePath+"my/Base/вопрос/78", {asd: 'asd'}, function (res) {
    }, "json");

    <a href="/blog/Test/asd/3" class="ajax">Click Me! 3</a><br>
    <a href="/blog/Test/asd/1" class="dialog">Результат - в попап 1</a><br>
    <a href="/blog/Test/asd/2" class="dialog">Результат - в попап 2</a><br>
    <a href="/blog/Test/asd/1" class="confirm" data-confirm="Are you sure?">Простой переход с подтверждением</a><br>
    <a href="/blog/Test/asd/1" class="ajax confirm" data-id="3" data-ajax-method="DELETE">Click Me! DELETE</a><br>


	<a href="/Blog/Test/asd/1/messages/Mess" class="ajax btn btn-default data-ajax-method">test</a>
	<a href="/test/ajax/asd/1/messages/zxc" id="ajax-callback-2" class="btn btn-default ajax">AJAX call (WORKS)</a>

 */

// $_this = $this;

return
    /**
     * @param string $question
     * @return \closure
     */
    function ($question = null, $asd = null, $вопрос, $messages = null) use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        //$app_object = Application\Bootstrap::getInstance();
		// Альтернатива $_this = $this;
        $app_object = app()->getInstance();

        /*
        // Тесты мейлера
        $mail = new \PHPMailer();

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 's205.avahost.net ';                    // Specify main and backup SMTP servers - 'smtp1.example.com;smtp2.example.com'
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'babagayr';                         // SMTP username - 'babagayr@example.com'
        $mail->Password = 'LJet118w3g';                       // SMTP password -
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->From = 'from@example.com';
        $mail->FromName = 'Mailer';
        $mail->addAddress('apanov@holbi.co.uk', 'Joe User');     // Add a recipient
        $mail->addAddress('wonderer@i.ua');               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        $mail->addAttachment('/home/babagayr/public_html/згидшс/шьфпуы/oak-list.png');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }

        //fb($mail);
        die;
        */

        if($asd == '10'){
            fb("asd = 10");

            // // Если вернуть результат через замыкание, на страницу попадет правильный json
            return function () { return array('zxc' => 'zzz'); };

            // Если вот так, на страницу полетит срендереннвй шаблон , преобразованный в json
            return array('zxc' => 'zzz');
        }

        // Тесты аджакса
        // Выведет строку в попап - это в одном примере,
        // В другом возвращает массив, который потом кодируется в джсон строку в Application.php line 1100 , метод render()
        // и там же выводится через echo, соответсвенно, здесь эхать нет смысла
        // Возможно, в другом варианте json можно эхать и здесь
        // [!] Если возвращать замыкание, возврпащающее массив, то схема работает
        // Если вернуть просто массив, то схема ломается
        if($asd == '2'){

            $res = array(
                "success" => 1,
                "result" => array(
                    "id" => 293,
                    "title" => "Event 1",
                    "url" => "http://example.com",
                    "class" => "event-important",
                    "start" => 12039485678000, // Milliseconds
                    "end" => 1234576967000 // Milliseconds
                ),
            );

            // При таком варианте возвращается отрендеренный шаблон
            // return $res;

            return function () use ($res) {

                echo "<b>Все работает!</b>";

                // return $res; // Выводит Array
            };
        }


        // Тесты аджакса
        /**
         * Вызов через:   <a href="/blog/Test/asd/1" class="dialog">Результат - в попап</a>
         *  Выведет всю морду, если использовтаь $app_object->useLayout('front_end.phtml');
         *  Выведет <b при $app_object->useJson(true);, но ошибку в консоли не выдвет!
         * [!] Здесь оно минует Application::process()
         */
        if($asd == '1'){
            // $app_object->useLayout('front_end.phtml');
            // $app_object->useJson(true);

            if(!is_null($messages))            fb($messages);

            $res = array(
                "success" => 1,
                "result" => array(
                    "id" => 293,
                    "title" => "Event 1",
                    "url" => "http://example.com",
                    "class" => "event-important",
                    "start" => 12039485678000, // Milliseconds
                    "end" => 1234576967000 // Milliseconds
                ),
            );



            // Не нужно
            // header("Content-type: applioation/json");

            // вернет срендеренный шаблон по умолчанию
            //return $res;

            //   // вернет срендеренный шаблон
            // return "Base.phtml";

            return function () {


                // echo "2"; // аналогично return

                // Вернет строку в попап "Base.phtml"
                return "Base.phtml";

                //header("Content-type: applioation/json");

               //echo ( json_encode($res) );
            };
        }


        /**
         * Вызывающая Конструкция:   <a href="/blog/Test/asd/3" class="ajax">Click Me! 3</a>
         * Выдает ошибку : SyntaxError: JSON.parse: unexpected character at line 1 column 1 of the JSON data
         * Response Text: <b
         * [!] Здесь идет через Application::process()
         * Если закоментить в Application::process() вызов useJson(), все работает
         * Обвал из-за $this->jsonFlag = true;
         * Закоментил ob_clean() в Application::render() - заработало
         * FIXME разобраться с реткрном
         */
        if($asd == '3'){

            // Работает
            // return "Test.phtml";

            // Аналогично, рендерится шаблон по умолчанию , т.е. Test.phtml
            // return 2;

            // Twig кидает ошибку: Unable to find template "{"a":4}"
            // return json_encode(array("a" => 4));

            return function () {

                // если class="ajax", выдаст ошибку      Cannot modify header information
                // если class="dialog" , выплюнет как html в попап
                // echo "2";

                //return json_encode(array(2));

                // если class="ajax", преобразует в массив ({, 2, })
                // если class="dialog" , выплюнет как html в попап
                return "{2}";
            };
        }

        if($asd == '4'){
            return function () use ($app_object) {
                // вывод мессаги в синий попап
                $app_object->getMessages()->addNotice('Notice Text');

                // Вернет корректный json
                return array("result" => "success", "param" => "value");
            };
        }



        ///fb($app_object->getRequest()->getParams());
       
        

        // FIXME Если отсюда поппытаться переререть zoqa_title, эффект нулевой
        // $view->zoqa_title = '$answer';
        $app_object->getLayout()->title('');

        //return array('question' => $question, 'answer' => $answer);


		// change layout
		$app_object->useLayout('front_end.phtml');
	
		// Такая конструкция загрузит шаблон
		// return 'Test.phtml';
		// Test.phtml грузится и так, по умолчанию
		// Что будет, если вернуть масив?
		// return array('question' => $question, 'answer' => $answer);
    };