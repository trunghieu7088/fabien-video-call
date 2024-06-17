<?php
/*
Template Name: Custom Video Call Stripe Call Back
*/
?>
<?php
//redirect none-logged user to homepage
if(!is_user_logged_in())
{
    wp_redirect(site_url());
}
get_header();
?>
<div class="container stripe-connect-container">
    <h2 class="stripe-connect-title">You are the coach and you need to connect Stripe Account before posting service or get payments</h2>
    <a href="<?php echo hanlding_stripe_connect_button(get_current_user_id()); ?>" class="stripe-connect-link">Connect Stripe</a>
</div>
<?php
get_footer();