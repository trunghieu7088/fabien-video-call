(function ($) {
    $(document).ready(function () {   

        $("#custom-post-service-form").submit(function(event){            
            event.preventDefault(); 
            var create_service_formdata = $("#custom-post-service-form").serialize();  
            
            $.ajax({

                type: "POST",
                url: custom_video_call_ajaxURL,
                dataType: 'json',
                data: create_service_formdata,
                beforeSend: function() {
                   
                },
                success: function(response) {   
                    
                    if(response.success=='true') 
                    {
                        
                        toastr.success(response.message);
                       // window.location.href=response.redirect_url;
                    }          
                    else
                    {
                        toastr.error('something went wrong. Please refresh !');
                    }      
                                       
                },   
                error: function(error) {                    
                    alert(error);
                }             
            });

        });


        $("#identify-role-form").submit(function(event){
            event.preventDefault(); 
            var formData = $("#identify-role-form").serialize();         
            $.ajax({

                type: "POST",
                url: custom_video_call_ajaxURL,
                dataType: 'json',
                data: formData,
                beforeSend: function() {
                   
                },
                success: function(response) {   
                    if(response.success=='true') 
                    {
                        toastr.success('Save succesfully');
                        window.location.href=response.redirect_url;
                    }          
                    else
                    {
                        toastr.error('something went wrong. Please refresh !');
                    }      
                                       
                },   
                error: function(error) {                    
                    alert(error);
                }             
            });
           
        });

        //init image uploader for service

        if($("#custom-post-service-form").length > 0)
        {
            if (typeof plupload !== 'undefined') {
                var service_image_uploader = new plupload.Uploader({
                    runtimes: 'html5,flash,silverlight,html4',
                    browse_button: 'service-image-upload-btn', // ID of the custom button
                    container: 'service-image-upload-container', // ID of the container for the uploader                           
                    url: custom_video_call_ajaxURL, // WordPress AJAX handler                       
                    multipart: true,      
                    multipart_params: {
                        action: 'service_image_uploader', // Custom AJAX action for handling the upload
                        _ajax_nonce: $("#service_image_uploader_nonce").val(), // Nonce for security                                              
                    },
                    filters: {
                        prevent_duplicates: true,
                        max_file_size: '20mb',
                        mime_types: [                        
                            { title: 'allowed files', extensions: 'png,jpg,jpeg,gif' },                        
                        ],
                    },
                    multi_selection: false, // Allow multiple file selection
                    max_file_count: 1,
                    init:
                    {
                        FilesAdded: function(up, files) 
                        {                           
                            service_image_uploader.start();
                        },
                        BeforeUpload: function(up,file)
                        {                                
                            
                        },                       
                        FileUploaded: function(up, file, response) {                                                        
                            var responseObject = JSON.parse(response.response);
                            //console.log(response);
                            $("#uploaded_image").css('display','inline-block');
                            $("#uploaded_image").attr('src',responseObject.url_uploaded);
                            $("#image_attach_id").val(responseObject.attach_id);
                        },
                        UploadComplete: function(up,file)
                        {
                            
                        },
                    },
                    
                })            
            };
            
            
            service_image_uploader.init();
            
        }        
        
    })
})(jQuery);