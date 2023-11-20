<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>OpenHub - Registration Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    <fieldset class="register_section">
        <h2>Sign up here:</h2>
        
        <div class="input_box">
            <label>First name:</label>
            <input type ="text" id="first_name" placeholder="First name" name="first_name">       
        </div>

        <div class="input_box">
            <label>Last Name:</label>
            <input type ="text" id="last_name" placeholder="Last name" name="last_name">
        </div>
                    
        <div class="input_box">
            <label>Username:</label>
            <input type ="text" id="username" placeholder="Username" name="username">
        </div>
                    
        <div class="input_box">
            <label>E-Mail:</label>
            <input type="text" id="email" placeholder="Email" name="email"> 
        </div>

        <div class="input_box">
            <label>Password:</label>
            <input type ="password" id="password" placeholder="Password" name="password">
        </div>
                   
        <button type="submit" class="register">Register</button><br>
        <button type="submit" name="log-in">Already an user?</button>   
        
    </fieldset>

    <?php include("./shared/footer.php"); ?>
</body>
</html>