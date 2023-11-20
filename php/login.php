<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>OpenHub - Login Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    <fieldset class="register_section">
        <h2>Log in here:</h2>
                    
        <div class="input_box">
            <label>E-Mail:</label>
            <input type="text" id="email" placeholder="Email" name="email"> 
        </div>

        <div class="input_box">
            <label>Password:</label>
            <input type ="password" id="password" placeholder="Password" name="password">
        </div>
                   
        <button type="submit" class="register">Log in</button><br> 
        
    </fieldset>

    <?php include("./shared/footer.php"); ?>
</body>
</html>