@if(Session::has('jgrowl'))
<script type="text/javascript">
    (function($){
        $(document).ready(function()
        {
            var msg = "<?php echo Session::get('jgrowl'); ?>";
            <?php if(Session::get('duration')) {?>
            var duration = <?php echo Session::get('duration'); ?>
            <?php } else if(isset($duration)) { ?>
            var duration = <?php echo $duration; ?>
            <?php } else {?>
            var duration = 1750;
            <?php } ?>

            $.jGrowl(msg, { life: duration });
        });
    })(jQuery);
</script>
@endif