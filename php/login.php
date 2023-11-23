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

    
    <main class="main_container">

        <fieldset class="register_section">
            <h2>Log in here:</h2>
                        
            <div class="inputBox">
                <label for="email">E-Mail:</label>
                <input type="email" id="email" name="email" placeholder="Email" required> 
            </div>

            <div class="inputBox">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <div class="inputBox">
                <label for="rememberme">Remember Me:</label>
                <input type="checkbox" id="rememberme" name="RememberMe" placeholder="RememberMe" required>
            </div>

            <button type="submit" class="formButton">Log in</button><br> 
            

        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>
</body>
</html>