<?php
/**
* Options Markups
* @package Mel-7
*
**/

?>

<div class="wrap">
    
    <h1><strong>Magic Line Nagivation - Light</strong></h1>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'magic_line_nav' );
            do_settings_sections( 'magic_line_nav' );
            submit_button();
        ?>
    </form>
</div> <?php