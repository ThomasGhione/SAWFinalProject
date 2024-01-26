<nav>

    <?php $root = "/chroot/home/S5311626/public_html"; ?>

    <div class="left_nav">
        <a href="/~S5311626/"><img class="navLogoImg" src="/~S5311626/images/bestLogo.png" alt="Website Logo, you can click on it to return to the homepage"></a>

        <div class="left-nav-buttons">
            <a href="/~S5311626/" class="navButton">Homepage</a>
            <?php
                if ($sessionManager->isSessionSet() && $sessionManager->isAdmin()) {
                    echo "<a class='blankSpace'></a>";
                    echo "<a class='navButton' href='/~S5311626/php/adminTools/adminTools.php'>Admin Tools</a>";
                }
            ?>
        </div>
    
    </div>

    <div class="right_nav">

        <?php
            
            if ($sessionManager->isSessionSet()) {
                echo "<div class='visit-options'>";
                    echo "<a class='navButton' href='/~S5311626/php/show_profile.php'>Personal Area</a>";
                    echo "<a class='blankSpace'></a>";
                    echo "<a class='navButton' href='/~S5311626/php/scripts/logout.php'>Logout</a>";
                echo "</div>";
            }
            else {
                echo "<div class='visit-options'>";
                    echo "<a class='navButton' href='/~S5311626/php/registrationForm.php'>Register</a>";
                    echo "<a class='blankSpace'></a>";
                    echo "<a class='navButton' href='/~S5311626/php/loginForm.php'>Login</a>";
                echo "</div>";
            }
        ?>

        <a class="blankSpace"></a>
        
        <form id="searchAreaForm" action="/~S5311626/php/searchArea.php" method="post">
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
                $dbManager->activateConn();
                $result = $dbManager->dbQueryWithParams("SELECT pfp FROM users WHERE email = ?", "s", [$sessionManager->getEmail()]);
                
                $row = $result->fetch_assoc();

                if ($row["pfp"] == NULL) 
                    $currentPfp = "default.jpg";
                else
                    $currentPfp = $sessionManager->getEmail();

                $dbManager->closeConn();

                echo "<a href='/~S5311626/php/show_profile.php'><img class='navUsrImg' src='/~S5311626/images/pfps/$currentPfp' alt='Your profile picture'></a>";
            }
        ?>
        
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("searchAreaForm").addEventListener("submit", function (event) {
                let searchBar = document.getElementById("searchBar").value.trim();

                if (searchBar == "") {
                    event.preventDefault();
                    alert("You must enter something to search!");
                }
            })
        })
    </script>


</nav> 