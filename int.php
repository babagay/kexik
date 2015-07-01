<?php

    $to      = 'wonderer@i.ua';
    $subject = 'the subject';
    $message = 'hello';
    $headers = 'From: webmaster@example.com' . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    echo 1;



echo "[root test]+ <br/>";
// Show all information, defaults to INFO_ALL
phpinfo();

?>