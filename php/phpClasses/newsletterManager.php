<?php
    
    // To create this class we used:
    // - The official documentation on github: 'https://github.com/PHPMailer/PHPMailer'
    // - An article from IONOS: 'https://www.ionos.it/digitalguide/e-mail/tecnica-e-mail/phpmailer/'
    // - An article from mailtrap.io: 'https://mailtrap.io/blog/phpmailer-gmail/'

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $root = $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject";
    
    require "$root/php/externalTools/PHPMailer/src/Exception.php";
    require "$root/php/externalTools/PHPMailer/src/PHPMailer.php";
    require "$root/php/externalTools/PHPMailer/src/SMTP.php";

    class newsletterManager {

        private $mail;

        function __construct() {
            // TODO Aggiungere try-catch sul costruttore, guarda al file sendEmail.php per capire di più cosa fare
            try {
                $this->mail = new PHPMailer (true);
                $this->mail->isSMTP();
                $this->mail->Host = "smtp.gmail.com";  //gmail SMTP server
                $this->mail->SMTPAuth = true;
                // to view proper logging details for success and error messages
                // $mail->SMTPDebug = 1;
                $this->mail->Host = "smtp.gmail.com";                                      //gmail SMTP server
                $this->mail->Username = "Tiananmen2002ChinaIsGoodChinaIsLife@gmail.com";   //email
                $this->mail->Password = "ynrk wasi eryl yhms" ;                            //16 character obtained from app password created
                $this->mail->Port = 465;                                                   //SMTP port
                $this->mail->SMTPSecure = "ssl";
            }
            catch (Exception $e) {
                $_SESSION["error"] = "";
                echo "newsletterManager error: ".$this->mail->ErrorInfo;
                header("Location: ,,/adminTools/manageNewsletter.php");
            }
        }
        
        function sendNewsletter($usrArr, $message) {
            // TODO Aggiungere Try-Catch al metodo, vedere sendEmail.php in scripts per capire di più

            if (empty($usrArr) || empty($message)) {
                // TODO Aggiungere caso di errore
            }

            $selectedUsers = [];
            $message = htmlspecialchars(trim($message));

            // Following code converts all emails with lower characters
            foreach ($usrArr as $selectedEmail) {
                $selectedEmail = strtolower($selectedEmail);

                if (filter_var($selectedEmail, FILTER_VALIDATE_EMAIL) === false) {
                    // TODO Aggiungere caso di errore
                }

                array_push($selectedUsers, $selectedEmail);                
            }


            foreach($selectedUsers as $email) {
                $this->mail->clearAddresses();
                $this->mail->addAddress($email);
                $this->mail->isHTML(true);
                $this->mail->Subject = "OpenHub - New Mail Received";
                $this->mail->Body = $message;
                $this->mail->send();
            }

            return true;
        }

        function setNewsletter($dbManager, $sessionManager, $email, $set) {
            // TODO Check errors on both $result and add Try-Catch
            
            $result = $dbManager->dbQueryWithParams("SELECT newsletter FROM users WHERE email = ?", "s", [$email]); 
            $row = $result->fetch_assoc();
            $isSubbed = $row["newsletter"];

            if ($set && !$isSubbed)
                $addFlag = true;    // Default: user wants to join newsletter
            elseif (!$set && $isSubbed)
                $addFlag = false;   // Otherwise: user wants to abandon newsletter

            $result = $dbManager->dbQueryWithParams("UPDATE users SET newsletter = ? WHERE email = ?", "is", [$addFlag, $email]);

            $sessionManager->setNewsletter(!$isSubbed);
        }
    }

?>