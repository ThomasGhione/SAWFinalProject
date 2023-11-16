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
            <img class="main_img" src="./images/BESTLOGO.png" alt="BESTLOGO">
        </section>

        <section class="column">
            <p class="desc">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            <p class="desc">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            <p class="desc">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            <p class="desc">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            <p class="desc">Furry ipsum dolor sit amet consectetur adipisicing elit.</p>
        </section>

        <section class="column">   
            <div class ="register_section">
                <fieldset>
            
                    <h2>
                        Sign up here:
                    </h2>
        
                    <label>Username:</label><br>
                    <input type ="text" id="username" placeholder="Username" name="username" maxlength="50" required><br>
                   
                    <label>Password:</label><br>
                    <input type ="password" id="password" placeholder="Password" name="password" maxlength="50" required><br>
                    
                    <button type="submit" class="register">Register</button><br>
                    <button type="submit" name="log-in">Already an user?</button>
        
                </fieldset>
            </div>
        </section>



    </main>





    
    <?php include("./php/shared/footer.php") ?>
    
</body>
</html>