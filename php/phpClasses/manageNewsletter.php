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

    class manageNewsletter {
        private $mail;

        function __construct() {
            $mail = new PHPMailer (true);
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";  //gmail SMTP server
            $mail->SMTPAuth = true;
            // to view proper logging details for success and error messages
            // $mail->SMTPDebug = 1;
            $mail->Host = "smtp.gmail.com";  //gmail SMTP server
            $mail->Username = "simo64.tomasella@gmail.com";   //email
            $mail->Password = "xzdp wpbc maja tpge" ;   //16 character obtained from app password created
            $mail->Port = 465;                    //SMTP port
            $mail->SMTPSecure = "ssl";
        }
        
        function sendNewsletter() {
            
            $selectedUsers = [];

            foreach ($_POST["sendEmail"] as $selectedEmail) 
                array_push($selectedUsers, $selectedEmail);
            $message = $_POST["message"];
        }

        function setNewsletter($dbManager, $email, $set) {
            // TODO Check errors on $result
            
            $result = $dbManager->dbQueryWithParams("SELECT newsletter FROM users WHERE email = ?", "s", [$email]); 
            $row = $result->fetch_assoc();
            $isSubbed = $row["newsletter"];

            // Following code adds user to the newsletter
            if ($set && !$isSubbed) {
                
            }
            elseif (!$set && $isSubbed) {  // Following code deletes user from the newsletter
            
            }


            // TODO Add errors in case something is wrong

            /*
            if ($row["newsletter"]) {
                
            } 
            else {
                $result = $dbManager->dbQueryWithParams("UPDATE users SET newsletter = 1", "s", [$email]);
            }
            */

        }
    }

?>