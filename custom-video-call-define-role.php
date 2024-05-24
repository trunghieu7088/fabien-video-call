<?php
/*
Template Name: Custom Video Call Define Role
*/
?>
<?php
get_header();
?>
<div class="container">
    <div class="row define-role-block">
        <div class="col-md-12 col-sm-12">
            <p class="roleTitle">Please tell us who you are</p>            
            <form class="define-role-form-block" id="identify-role-form">
                <input type="hidden" name="action" value="custom_video_call_save_role">
                <p><input type="radio" id="coach_selector" name="role_type" value="coach"> <span><strong>Coach </strong></span></p>
                <p><input type="radio" id="student_selector" name="role_type" value="trainee"><span> <strong> Trainee </strong></span></p>
                <div class="submit-button-container">
                    <button type="submit" class="custom-save-role-btn">Save</button>
                </div>
                
            </form>
        </div>
    </div>
</div>
<?php
get_footer();
?>