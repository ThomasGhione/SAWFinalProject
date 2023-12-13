<nav>

    <?php 
        $root = "/SAW/SAWFinalProject";
    ?>

    <div class="left_nav">
        <a href="<?php echo $root; ?>/index.php"><img class="navLogo" src="<?php echo $root; ?>/images/bestLogo.png" alt="Website Logo"></a>
        <a class="navButton">Homepage</a>
        <a class="navButton">Dashboard</a>
        <a class="navButton">Explore</a>
    </div>

    <div class="right_nav">

        <?php
            // TODO Creare una funzione di check che controlli se l'oggetto sessionManager esiste effettivamente
            
            if ($sessionManager->isSessionSet()) {
                echo '<a class="navButton" href="' . $root . '/php/scripts/logout.php">Logout</a>';
                if ($sessionManager->isAdmin()) 
                    echo '<a class="navButton" href="' . $root . '/php/adminTools/adminTools.php">Admin Tools</a>';
            }
            else {
                echo '<a class="navButton" href="' . $root . '/php/registrationForm.php">Register here!</a>';
                echo '<a class="navButton" href="' . $root . '/php/loginForm.php">Login</a>';
            }
        ?>

        <a class="blankSpace"></a>

        <span class="fa fa-search" aria-hidden="true"> </span>
        <input type="text" id="search_bar" placeholder="Search repos or users..." name="search_bar" maxlength="128">   

        <?php if ($sessionManager->isSessionSet()) echo '<button alt="Your profile">ph</button>'; ?>
    </div>

</nav>