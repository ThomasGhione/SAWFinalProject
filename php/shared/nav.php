<nav>

    <?php $root = "/SAW/SAWFinalProject"; ?>

    <div class="left_nav">
        <a href="<?php echo $root; ?>/index.php"><img class="navLogo" src="<?php echo $root; ?>/images/bestLogo.png" alt="Website Logo"></a>
        <a class="navButton">Homepage</a>
        <a class="navButton">Dashboard</a>
        <a class="navButton">Explore</a>
    </div>

    <div class="right_nav">

        <?php
            require("$root/php/phpClasses/sessionManager.php");
            $sessionManager = new sessionManager();

            if ($sessionManager->isSessionSet())
                echo '<a class="navButton" href="' . $root . '/scripts/logout.php">Logout</a>';
            else {
                echo '<a class="navButton" href="' . $root . '/php/registration.php">Register here!</a>';
                echo '<a class="navButton" href="' . $root . '/php/login.php">Login</a>';
            }
        ?>

        <a class="blankSpace"></a>

        <i class="fa fa-search" aria-hidden="true"></i>
        <input type="text" id="search_bar" placeholder="Search repos or users..." name="search_bar" maxlength="128">   

        <button alt="Your profile">ph</button>
    </div>

</nav>