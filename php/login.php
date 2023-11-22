<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>OpenHub - Login Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    
    <main class=main_container>

        <fieldset class="register_section">
            <h2>Log in here:</h2>
                        
            <div class="inputBox">
                <label>E-Mail:</label>
                <input type="text" id="email" placeholder="Email" name="email"> 
            </div>

            <div class="inputBox">
                <label>Password:</label>
                <input type="password" id="password" placeholder="Password" name="password">
            </div>

            <div class="inputBox">
                <label>Remember Me:</label>
                <input type="checkbox" id="RememberMe" placeholder="RememberMe" name="RememberMe">
            </div>

            <button type="submit" class="formButton">Log in</button><br> 
            

        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>
</body>
</html>