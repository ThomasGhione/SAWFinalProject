<?php
    
    // To create this class we used:
    // - The official documentation on github: 'https://github.com/PHPMailer/PHPMailer'
    // - An article from IONOS: 'https://www.ionos.it/digitalguide/e-mail/tecnica-e-mail/phpmailer/'
    // - An article from mailtrap.io: 'https://mailtrap.io/blog/phpmailer-gmail/'

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $root = "/chroot/home/S5311626/public_html";
    
    require "$root/php/externalTools/PHPMailer/src/Exception.php";
    require "$root/php/externalTools/PHPMailer/src/PHPMailer.php";
    require "$root/php/externalTools/PHPMailer/src/SMTP.php";

    class newsletterManager {

        private $mail;

        function __construct() {
            try {
                $this->mail = new PHPMailer (true);
                $this->mail->isSMTP();
                $this->mail->Host = "smtp.gmail.com";  //gmail SMTP server
                $this->mail->SMTPAuth = true;
                // to view proper logging details for success and error messages
                // $mail->SMTPDebug = 1;
                $this->mail->Host = "smtp.gmail.com";                                      //gmail SMTP server
                $this->mail->Username = "sawfinalprojecttomasellaghione@gmail.com";        //email
                $this->mail->Password = "nqwm xclx qstr vssn" ;                            //16 character obtained from app password created
                $this->mail->Port = 465;                                                   //SMTP port
                $this->mail->SMTPSecure = "ssl";
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                echo "newsletterManager error: ".$this->mail->ErrorInfo;
            }
        }
        
        function sendNewsletter(array &$userArr, string &$message): bool {
            try {
                if (empty($userArr) || empty($message)) {
                    error_log("[" . date("Y-m-d H:i:s") . "] newsletterManager: empty message or user array". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Message can't be empty");
                }

                $selectedUsers = [];
                $message = htmlspecialchars(trim($message));
    
                // Following code converts all emails with lower characters
                foreach ($userArr as $selectedEmail) {
                    $selectedEmail = strtolower($selectedEmail);
    
                    if (filter_var($selectedEmail, FILTER_VALIDATE_EMAIL) === false) {
                        error_log("[" . date("Y-m-d H:i:s") . "] newsletterManager: invalid email". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                        throw new Exception("Invalid email");
                    }
    
                    array_push($selectedUsers, $selectedEmail);                
                }

            } catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }

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

        function setNewsletter(&$dbManager, &$sessionManager, string $email, bool &$set): void {
            $dbManager->activateConn();
            $result = $dbManager->dbQueryWithParams("SELECT newsletter FROM users WHERE email = ?", "s", [$email]); 
            $row = $result->fetch_assoc();
            $isSubbed = $row["newsletter"];

            if ($set && !$isSubbed)
                $addFlag = true;    // Default: user wants to join newsletter
            elseif (!$set && $isSubbed)
                $addFlag = false;   // Otherwise: user wants to abandon newsletter

            $result = $dbManager->dbQueryWithParams("UPDATE users SET newsletter = ? WHERE email = ?", "is", [$addFlag, $email]);

            $sessionManager->setNewsletter(!$isSubbed);
            $dbManager->closeConn();
        }
    }

?>