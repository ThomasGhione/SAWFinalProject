<nav>

    <?php 
        $root = "/SAW/SAWFinalProject";
    ?>

    <div class="left_nav">
        <a href="<?php echo $root; ?>/index.php"><img class="navLogoImg" src="<?php echo $root; ?>/images/bestLogo.png" alt="Website Logo, you can click on it to return to the homepage"></a>
        <div class="big-right-nav">
            <a href="<?php echo $root; ?>/index.php" class="navButton">Homepage</a>
            <a class="blankSpace"></a>
        </div>

        <?php
            if ($sessionManager->isSessionSet())
                if ($sessionManager->isAdmin()) {
                    echo "<div class='admin-small-right-nav'>";
                        echo "<a class='navButton' href='$root/index.php'>Homepage</a>";
                        echo "<a class='navButton' href='$root/php/adminTools/adminTools.php'>Admin Tools</a>";
                    echo "</div>";
                }
        ?>
    
    </div>

    <div class="right_nav">

        <?php
            
            if ($sessionManager->isSessionSet()) {
                if ($sessionManager->isAdmin()) {
                    echo "<div class='admin-button'>";    
                        echo "<a class='navButton' href='" . $root . "/php/adminTools/adminTools.php'>Admin Tools</a>";
                        echo "<a class='blankSpace'></a>";
                    echo "</div>";
                }
                
                echo "<div class='visit-options'>";
                    echo "<a class='navButton' href='" . $root . "/php/show_profile.php'>Personal Area</a>";
                    echo "<a class='blankSpace'></a>";
                    echo "<a class='navButton' href='" . $root . "/php/scripts/logout.php'>Logout</a>";
                echo "</div>";
            }
            else {
                echo "<div class='visit-options'>";
                    echo "<a class='navButton' href='" . $root . "/php/registrationForm.php'>Register</a>";
                    echo "<a class='blankSpace'></a>";
                    echo "<a class='navButton' href='" . $root . "/php/loginForm.php'>Login</a>";
                echo "</div>";
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

                echo "<a href=$root/php/show_profile.php><img class='navUsrImg' src='$root/images/pfps/$currentPfp' alt='Your profile picture'></a>";
            }
        ?>
        
    </div>

</nav> 