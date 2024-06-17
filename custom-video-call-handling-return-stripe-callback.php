<?php
/*
Template Name: Custom Video Call Handling Stripe Return
*/
?>
<?php
get_header();
?>
<?php 
do_action('connect_stripe_callback');
?>
<div class="container connect-stripe-result">
<h3>You have connected your Stripe account successfully !</h3>
<a class="stripe-post-service-link" href="<?php echo site_url('custom-post-service'); ?>">Post Service</a>
</div>
<?php
get_footer();