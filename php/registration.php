<?php
    require("./scripts/errInitialize.php");
    require('./phpClasses/sessionManager.php');

    $sessionManager = new sessionManager();

    // TODO Code to check if cookie is set
    if ( $sessionManager->isSessionSet() ) {
        header('Location: ./personalArea.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['serverStatus'] = 'POST';
        $_SESSION['postData'] = $_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['email'] . ' ' . $_POST['pass'] . ' ' . $_POST['confirm'];
        header('Location: ./scripts/registrationForm.php');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Registration Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    <div class="bg-image"></div>

    <main class=main_container>
        <fieldset class="register_section">
            <h2>Sign up here:</h2>
            

            <?php
                if(isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                else
                    echo "<p class='error'>&nbsp;</p>";
            ?>


            <form action="registration.php" method="post">
                
                <div class='inputBox'>
                    <label for='firstname'>Firstname: </label>
                    <input required type='text' id='firstname' name='firstname' placeholder='Firstname (required)'>
                </div>
                
                <div class='inputBox'>
                    <label for='lastname'>Lastname: </label>
                    <input required type='text' id='lastname' name='lastname' placeholder='Lastname (required)'>
                </div>

                <div class='inputBox'>
                    <label for='email'>Email: </label>
                    <input required type='email' id='email' name='email' placeholder='Email (required)'>
                </div>

                <div class='inputBox'>
                    <label for='pass'>Password: </label>
                    <input required type='password' id='pass' name='pass' placeholder='Password (required)'>
                </div>

                <div class='inputBox'>
                    <label for='confirm'>Confirm Password: </label>
                    <input required type='password' id='confirm' name='confirm' placeholder='Confirm Password (required)'>
                </div>

                <input type="submit" class='formButton' name="submit" value='Submit'>

                <a class="formButton" href="/SAW/SAWFinalProject/php/login.php">Already a user?</a>
            </form>






        <!--

            <form method="post" action="./scripts/registrationForm.php">
            
                <div class="inputBox">
                    <label for="firstName">First name: </label>
                    <input required type ="text" id="firstName" name="firstName" placeholder="First name (obbligatorio)" value="<?php echo $firstName ?>">       
                </div>
                
                <div class="inputBox">
                    <label for="lastName">Last Name: </label>
                    <input required type ="text" id="lastName" name="lastName" placeholder="Last name (obbligatorio)" value="<?php echo $lastName ?>">
                </div>
                                
                <div class="inputBox">
                    <label for="userName">Username: </label>
                    <input required type ="text" id="userName" name="userName" placeholder="Username (obbligatorio)" value="<?php echo $username ?>">
                </div>
                                
                <div class="inputBox">
                    <label for="email">E-Mail: </label>
                    <input required type="email" id="email" name="email" placeholder="Email (obbligatorio)" value="<?php echo $email ?>"> 
                </div>
                
                <div class="inputBox">
                    <label for="password">Password: </label>
                    <input required type="password" id="password" name="password" placeholder="Password (obbligatorio)" value="<?php echo $password ?>">
                </div>
                
                <div class="inputBox">
                    <label for="confirmPwd">Confirm Password: </label>
                    <input required type="password" id="confirmPwd" name="confirmPwd" placeholder="Confirm Password (obbligatorio)" value="<?php echo $firstName ?>">
                </div>

                <div class="inputBox">
                    <label for="gender">Gender: </label>
                    <select name="gender" id="gender">
                        <option value="notSpecified">Preferisco non specificare</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="inputBox">
                    <label for="birthdate">Birthdate: </label>
                    <input type="date" id="birthdate" name="birthdate">
                </div>

                <input type="submit" class="formButton" value="Submit">
                <br>
                <a class="formButton" href="/SAW/SAWFinalProject/php/login.php">Already a user?</a>
            
            </form>

        -->

        </fieldset>
    </main>

    <?php include("./shared/footer.php"); ?>
</body>
</html>