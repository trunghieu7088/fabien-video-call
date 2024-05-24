<?php
/*
Template Name: Custom Video Call Profile Page
*/
?>
<?php
get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 custom-video-call-profile-container">
            <div class="custom-video-call-profile-header">
                <div class="avatar-container">
                    <img src="http://fabien.et/wp-content/uploads/2024/05/sample_avatar.jpg">
                    <p><button>Change</button></p>
                </div>
            </div>
            <div class="custom-video-call-profile-body">                
                <div class="custom-video-call-inputs">
                    <p class="custom-video-call-form-title">Personal Information</p>
                    <input type="text" name="display_name" id="display_name" placeholder="Full Name">
                    <input type="text" name="expertise" id="expertise" placeholder="Expertise">
                    <textarea id="bio" name="bio" class="custom-video-call-profile-bio" rows="4" cols="50" placeholder="Bio & Exp"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();