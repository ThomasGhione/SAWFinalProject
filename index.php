<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./CSS/style.css">
    <title>OpenHub Homepage</title>
</head>

<body>
    <?php include("./php/shared/nav.php") ?>

    <main class="main_container">
        
        <section class="column">
            <img src="./images/BESTLOGO.png" alt="BESTLOGO">
        </section>

        <section class="column">
            <div class="second_column">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>    
            </div>
        </section>

        <section class="column">   
                <fieldset class="register_section">
                    <h2>Sign up here:</h2>
        
                    <div class="input_box">
                        <label>First name:</label>
                        <input type ="text" id="first_name" placeholder="First name" name="first_name" maxlength="64" required>       
                    </div>

                    <div class="input_box">
                        <label>Last Name:</label>
                        <input type ="text" id="last_name" placeholder="Last name" name="last_name" maxlength="64" required>
                    </div>
                    
                    <div class="input_box">
                        <label>Username:</label>
                        <input type ="text" id="username" placeholder="Username" name="username" maxlength="64" required>
                    </div>
                    
                    <div class="input_box">
                        <label>Password:</label>
                        <input type ="password" id="password" placeholder="Password" name="password" maxlength="64" required>
                    </div>
                    
                    <button type="submit" class="register">Register</button><br>
                    <button type="submit" name="log-in">Already an user?</button>   
        
                </fieldset>
        </section>

    </main>



    <?php include("./php/shared/footer.php") ?>
</body>
</html>