<footer>

    <?php 
        $root = "/SAW/SAWFinalProject";
    ?>

    <address>
        <p>&copy; 2023 - All rights reserved - <a class="footerLink" href="<?php echo $root?>/php/termsAndConditions.php">See our terms and conditions</a></p>
        <i>This site is a great replacement for an ancient way to manage repositories.</i>
        <a class="footerLink" href="<?php echo $root?>/php/shared/listOfReasons.php">Learn more about this</a>
        
        <a> - </a>

        <?php
            if (isset($_SESSION["newsletter"]) && !$_SESSION["newsletter"])
                echo "<a class='footerLink' href='$root/php/scripts/manageUserInNewsletter.php?sub=" . "true" . "'>Join our newsletter!</a>";
        ?>
    </address>
    
</footer>