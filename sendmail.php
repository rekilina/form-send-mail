<?php
    // classes, namespaces
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\OAuthTokenProvider;
    // source files
    require 'PHPMailer-6.6.5/src/Exception.php';
    require 'PHPMailer-6.6.5/src/PHPMailer.php';
    require 'PHPMailer-6.6.5/src/SMTP.php';
    require 'PHPMailer-6.6.5/src/OAuthTokenProvider.php';

    // объявляем объект класса PHPMailer
    $mail = new PHPMailer(true);
    // Обязательно указывает кодировку
    $mail->CharSet = 'UTF-8';
    $mail->setLanguage('ru', 'PHPMailer-6.6.5/language/');
    // Обязательно подключаем возможность использовать HTML теги в письме
    $mail->IsHTML(true);

    $mail->isSMTP();            
    //Set SMTP host name                          
    $mail->Host = "smtp.gmail.com";
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;                          
    //Provide username and password     
    $mail->Username = "yourmail@gmail.com";                 
    $mail->Password = "";  // from your mailbox                         
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";                           
    //Set TCP port to connect to
    $mail->Port = "587";       


    $mail->setFrom('yourmail@gmail.com');
    // $mail->FromName = 'Irina';
    // Кому отправить
    $mail->addAddress('iv.shebarshina@gmail.com');
    // Тема письма
    $mail->Subject = "Привет! ";


    // Рука
    $hand = "Правая";
    if($_POST['hand'] == 'left') {
        $hand = "Левая";
    }

    // Email Body
    $body = '<h1>Письмо отправлено через форму с помощью PHPMailer</h1>';

    if(trim(!empty($_POST['user_name']))) {
        $body.='<p><strong?>Name:</strong> '.$_POST['user_name'].'</p>';
    }
    if(trim(!empty($_POST['user_email']))) {
        $body.='<p><strong?>Email:</strong> '.$_POST['user_email'].'</p>';
    }
    if(trim(!empty($_POST['hand']))) {
        $body.='<p><strong?>Hand:</strong> '.$hand.'</p>';
    }
    if(trim(!empty($_POST['age']))) {
        $body.='<p><strong?>Age:</strong> '.$_POST['age'].'</p>';
    }
    if(trim(!empty($_POST['message']))) {
        $body.='<p><strong?>Message:</strong> '.$_POST['message'].'</p>';
    }


    // Прикрепить файл
    if(!empty($_FILES['image']['tmp_name'])) {
        // путь загрузки фала
        $filePath = __DIR__ . "/files/" . $_FILES['image']['name'];
        // грузим файл
        if(copy($_FILES['image']['tmp_name'], $filePath)) {
            $fileAttach = $filePath;
            $body.='<p><strong>Фото в приложении</strong>';  // ------------------------ </p>
            $mail->addAttachment($fileAttach);
        }
    }

    $mail->Body = $body;

    // Sending!
    // if(!$mail->send()) {
    //     $message = 'Error! In PHP script sendmail.php.';
    // } else {
    //     $message = "Successfully sent!";
    // }

    try {
        $mail->Send();
        echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    
    $response = ['message' => $message];

    header('Content-type: application/json');
    echo json_encode($response)

?>
