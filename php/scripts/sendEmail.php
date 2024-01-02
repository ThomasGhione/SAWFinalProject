<?php

    // To create this script we used:
    // - The official documentation on github: 'https://github.com/PHPMailer/PHPMailer'
    // - An article from IONOS: 'https://www.ionos.it/digitalguide/e-mail/tecnica-e-mail/phpmailer/'
    // - An article from mailtrap.io: 'https://mailtrap.io/blog/phpmailer-gmail/'

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "../externalTools/PHPMailer\src\Exception.php";
    require "../externalTools/PHPMailer\src\PHPMailer.php";
    require "../externalTools/PHPMailer\src\SMTP.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            
            // TODO CONTROLLARE EVENTUALI DATI VUOTI

            $selectedUsers = [];
            foreach ($_POST["sendEmail"] as $selectedEmail) {
                array_push($selectedUsers, $selectedEmail);
            }

            $message = $_PAST["message"];


            // Tentativo di creazione di una nuova istanza della classe PHPMailer
            $mail = new PHPMailer (true);
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";  //gmail SMTP server
            $mail->SMTPAuth = true;
            //to view proper logging details for success and error messages
            // $mail->SMTPDebug = 1;
            $mail->Host = "smtp.gmail.com";  //gmail SMTP server
            $mail->Username = "simo64.tomasella@gmail.com";   //email
            $mail->Password = "xzdp wpbc maja tpge" ;   //16 character obtained from app password created
            $mail->Port = 465;                    //SMTP port
            $mail->SMTPSecure = "ssl";
            
            foreach($selectedUsers as $email) {
                $mail->clearAddresses();
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "OpenHub - New Mail Received";
                $mail->Body = $message;
                $mail->send();
            }

            // Send mail   
            if (!$mail->send()) {
                echo 'Email not sent an error was encountered: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent.';
            }

            $mail->smtpClose();

    
        } catch (Exception $e) {
            echo "Mailer Error: ".$mail->ErrorInfo;
        }
    }
    else {
        // TODO MISSING ERROR CHECKING FOR OTHER REQUESTS
    }



?>