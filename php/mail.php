<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function sendMailAttachment($userName, $files)
{
    $mail = new PHPMailer;
    try {
        $msg = "ok";
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth   = true;

        $mail->Host       = 'smtp.gmail.com';
        $mail->Username   = 'deadboy7967@gmail.com';
        $mail->Password   = 'xsueeanevgcttupn';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('deadboy7967@gmail.com', 'Новый абитуриент');

        $mail->addAddress('testdeveloper7967@gmail.com');

        if (!empty($files)) {
            foreach ($files as $key => $value) {
                $uploadfile = tempnam(sys_get_temp_dir(), sha1($value['name']));
                $filename = $value['name'];
                if (move_uploaded_file($value['tmp_name'], $uploadfile)) {
                    $mail->addAttachment($uploadfile, $filename);
                } else {
                    $msg .= 'Неудалось прикрепить файл ' . $uploadfile;
                }
            }
        }

        $mail->isHTML(true);

        $mail->Subject = 'Новый абитуриент';
        $mail->Body    = "<b>Имя:</b> $userName <br>";

        if ($mail->send()) {
           // echo "$msg";
            return true;
        } else {
            //echo "Сообщение не было отправлено. Неверно указаны настройки вашей почты";
            return false;
        }
    } catch (Exception $e) {
        //echo "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
        return false;
    }
}
