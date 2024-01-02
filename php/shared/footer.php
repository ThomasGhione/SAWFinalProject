<footer>

    <?php 
        $root = "/SAW/SAWFinalProject";
    ?>

    <address>
        <p>&copy; 2023 - All rights reserved</p>
        <a>This site is a great replacement for an ancient way to manage repositories.</a>
        <a href="<?php echo $root?>/php/shared/listOfReasons.php">Click here to see why</a>
        
        <!-- TODO Add control for joining newsletter -->
        <?php
            if (isset($_SESSION["newsletter"]) && !$_SESSION["newsletter"])
                echo "<a href='$root/php/scripts/manageUserInNewsletter.php?sub='" . "true" . "'> - Click here to join our newsletter!</a>";
        ?>
    </address>
    
</footer>