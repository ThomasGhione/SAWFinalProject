<?php
    require("../../shared/initializePage.php");    

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../../index.php");
        exit;
    }

    require_once("../../phpClasses/newsletterManager.php");
    $newsletterManager = new newsletterManager();

    try {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            error_log("Invalid request", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Invalid request");
        }

        if (!isset($_POST["selectedUsers"]) || empty($_POST["selectedUsers"])) {
            error_log("No user selected", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("No user selected");
        }

        if (!isset($_POST["message"]) || empty($_POST["message"])) {
            error_log("Message can't be empty", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Message can't be empty");
        }

        $message = $_POST["message"];
        $usrArr = explode(",", $_POST["selectedUsers"]);

        $newsletterManager->sendNewsletter($usrArr, $message);
    }
    catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }

    header("Location: ../manageNewsletter.php");
    exit;



    /*

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
            // CONTROLLARE EVENTUALI DATI VUOTI

            $selectedUsers = [];
            
            
            if (!isset($_POST)) {
                error_log(admin);
                throw new Exception(utente);
            }

            foreach ($_POST["sendEmail"] as $selectedEmail) 
                array_push($selectedUsers, $selectedEmail);

            $message = $_POST["message"];


            $mail = new PHPMailer (true);                     // trying to create a new PHPMailer instance 
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";                   // gmail SMTP server
            $mail->SMTPAuth = true;
            //to view proper logging details for success and error messages
            // $mail->SMTPDebug = 1;
            $mail->Host = "smtp.gmail.com";                   //gmail SMTP server
            $mail->Username = "simo64.tomasella@gmail.com";   //email
            $mail->Password = "xzdp wpbc maja tpge" ;         //16 character obtained from app password created
            $mail->Port = 465;                                //SMTP port
            $mail->SMTPSecure = "ssl";
            
            foreach ($selectedUsers as $email) {
                $mail->clearAddresses();
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "OpenHub - New Mail Received";
                $mail->Body = $message;
                $mail->send();
            }

            $mail->smtpClose();

            $_SESSION["success"] = "All mails sended correctly";
            header("Location: ../adminTools/manageNewsletter.php");
    
        } catch (Exception $e) {
            // MISSING ERROR CHECKING, COMPLETE IT
            $_SESSION["error"] = "";
            echo "Mailer Error: ".$mail->ErrorInfo;
            header("Location: ,,/adminTools/manageNewsletter.php");
        }
    }
    else {
        // MISSING ERROR CHECKING FOR OTHER REQUESTS
        $_SESSION["error"] = "";
        header("Location: ../adminTools/manageNewsletter.php");
    }

    */


?>