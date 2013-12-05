var userData = {
    name           : "<?php echo Core::getUser()->getName(); ?>",
    id             : <?php echo Core::getUser()->getId(); ?>,
    updateInterval : <?php echo AJAX_UPDATE_INTERVAL * 1000 ?>
}
