<nav>

    <?php 
        $root = "/SAW/SAWFinalProject";
    ?>

    <div class="left_nav">
        <a href="<?php echo $root; ?>/index.php"><img class="navImg" src="<?php echo $root; ?>/images/bestLogo.png" alt="Website Logo"></a>
        <a class="navButton">Homepage</a>
        <a class="blankSpace"></a>
        <a class="navButton">Dashboard</a>
        <a class="blankSpace"></a>
        <a class="navButton">Explore</a>
    </div>

    <div class="right_nav">

        <?php
            // TODO Creare una funzione di check che controlli se l'oggetto sessionManager esiste effettivamente
            
            if ($sessionManager->isSessionSet()) {
                echo "<a class='navButton' href='" . $root . "/php/show_profile.php'>Personal Area</a>";
                echo "<a class='blankSpace'></a>";
                echo "<a class='navButton' href='" . $root . "/php/scripts/logout.php'>Logout</a>";
                if ($sessionManager->isAdmin()) {
                    echo "<a class='blankSpace'></a>";
                    echo "<a class='navButton' href='" . $root . "/php/adminTools/adminTools.php'>Admin Tools</a>";
                }
            }
            else {
                echo "<a class='navButton' href='" . $root . "/php/registrationForm.php'>Register here!</a>";
                echo "<a class='blankSpace'></a>";
                echo "<a class='navButton' href='" . $root . "/php/loginForm.php'>Login</a>";
            }
        ?>

        <a class="blankSpace"></a>
        
        <form action="<?php echo $root; ?>/php/searchArea.php" method="post">
            <div class="searchBox">
                <div class="inputBox">
                    <label for="searchBar">Search: </label>
                    <input type="text" class="searchBar" id="searchBar" name="searchBar" placeholder="Search repos or users..." maxlength="128">   
                </div>

                <input class="formButton" type="submit" value="Search">
            </div>
        </form>

        <?php
            if ($sessionManager->isSessionSet()) {
                $result = $dbManager->dbQueryWithParams("SELECT pfp FROM users WHERE email = ?", "s", [$sessionManager->getEmail()]);
                
                $row = $result->fetch_assoc();

                if ($row["pfp"] == NULL) 
                    $currentPfp = "default.jpg";
                else
                    $currentPfp = $sessionManager->getEmail();

                echo "<a href=$root/php/show_profile.php><img class='navImg' src='$root/images/pfps/$currentPfp' alt='Your profile picture'></a>";
            }
        ?>
    </div>

</nav> 