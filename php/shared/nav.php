<nav>

    <?php $root = "/SAW/SAWFinalProject"; ?>

    <div class="left_nav">
        <a href="<?php echo $root; ?>/index.php"><img class="navLogo" src="<?php echo $root; ?>/images/bestLogo.png" alt="Website Logo"></a>
        <a class="navButton">Homepage</a>
        <a class="navButton">Dashboard</a>
        <a class="navButton">Explore</a>
    </div>

    <div class="right_nav">
        <a class="navButton" href="<?php echo $root; ?>/php/registration.php">Register here!</a>
        <a class="navButton" href="<?php echo $root; ?>/php/login.php">Login</a>

        <a class="blankSpace"></a>

        <i class="fa fa-search" aria-hidden="true"></i>
        <input type="text" id="search_bar" placeholder="Search repos or users..." name="search_bar" maxlength="128">   

        <button alt="Your profile">ph</button>
    </div>

    <?php // TODO preparare versione quando ci si logga?>

</nav>