(function ($) {
    $(document).ready(function () {   

        $("#meeting_type").change(function(){
            if($("#meeting_type").val()=='face-to-face')
            {
                $("#meettype_location").parent().css('display','block');
                $("#meettype_location").attr('required','required');
            }
            else
            {
                $("#meettype_location").parent().css('display','none');
                $("#meettype_location").removeAttr('required');
            }
        });

        $("#custom-post-service-form").submit(function(event){            
            event.preventDefault(); 
            var create_service_formdata = $("#custom-post-service-form").serialize();  
            
            $.ajax({

                type: "POST",
                url: custom_video_call_ajaxURL,
                dataType: 'json',
                data: create_service_formdata,
                beforeSend: function() {
                    $("#custom-post-service-form").attr('disabled','disabled');
                },
                success: function(response) {   
                    
                    if(response.success=='true') 
                    {                        
                        toastr.success(response.message);
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

        //get busy time to pass to the calendar
        var busy_time_list=[];
        if($("#busy-time-list").length > 0)
        {
            busy_time_list= JSON.parse($("#busy-time-list").html());            
        }
        

        if($("#custom-video-calendar").length > 0 )
        {
            var calendarEL=document.querySelector('#custom-video-calendar');

            var calendar = new FullCalendar.Calendar(calendarEL, {  
                headerToolbar:
                {
                    left: 'title',
                    center: '',
                    right: 'prev,next',
                },           
                initialView: 'dayGridMonth',
                contentHeight:"auto", 
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: 'short'
                },    
                events: busy_time_list,
              /*  events: [
                    {
                      title  : 'Busy',
                      start  : '2024-06-03T12:30:00',                                           
                    },
                    {
                      title  : 'Busy',
                      start  : '2024-06-05T12:30:00',                    
                    },                    
                  ],*/
                
                dateClick: function(info) {
                   // console.log(info.dateStr);
                    //console.log(info);

                    let today = new Date();
                    today.setHours(0, 0, 0, 0);
        
                    if (info.date < today) {
                        alert("You cannot choose a past date.");
                        return; 
                    }
                    
                    $("#selected_date").val(info.dateStr);
                    let remove_old_selected=document.querySelector(".custom_date_chosen"); 
                    if(remove_old_selected !== null)
                    {
                        remove_old_selected.classList.remove('custom_date_chosen');
                    }
                    info.dayEl.classList.add('custom_date_chosen');
                  },          

            });
            calendar.render();
        }

        //function to convert time
        function convert_time_to_stringtime(timestring)
        {
            const dateObject = new Date(timestring);
            // Formats the time according to your locale
            const formattedTime = dateObject.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
            $("#selected_time").val(formattedTime);            
        }
       
        $(".fc-day").mouseover(function(){
                $(this).css('cursor','pointer');
                $(this).addClass('custom_date_hover');
        });

        $(".fc-day").mouseout(function(){
          
            $(this).removeClass('custom_date_hover');
        });

        if( $("#selected-time-area").length > 0)
        {
            $("#selected-time-area").timepicker({
                timeFormat: 'hh:mm p',
                defaultTime: '01',
                change: function(time) {
                    convert_time_to_stringtime(time);                
                },
            });

            //var today_time_string = new Date().toJSON().slice(0,10);            
        }
        
        //init stripe
        if($('.custom-video-service-checkout-form-wrapper').length > 0)
        { 
            var stripe = Stripe(custom_stripe_public_key);
            var elements = stripe.elements();
            var card = elements.create('card');
            card.mount('#custom-card-element');

            document.querySelector('#custom-video-service-checkout-form').addEventListener('submit', function(e) {
                e.preventDefault();
                var formdata=$("#custom-video-service-checkout-form").serialize();
                $.ajax({

                    type: "POST",
                    url: custom_video_call_ajaxURL,
                    dataType: 'json',
                    data: formdata,
                    beforeSend: function() {
                       //block form here
                       $('.custom-video-service-checkout-form-wrapper').css('opacity','0.5');
                       $("#custom-video-service-checkout-btn").attr('disabled','disabled');
                       toastr.options.timeOut = 10000;
                       toastr.warning('Sending Payment !!');
                    },
                    success: function(response) {   
                        if(response.success=='true')
                        {                            
                            stripe.confirmCardPayment(response.client_secret, {
                                payment_method: {
                                    card: card,
                                    billing_details: {
                                        name: $("#billing_name").val(),
                                    }
                                }
                            }).then(function(result) {
                                if (result.error) {
                                    toastr.error('Something went wrong. Please refresh payment!!');
                                    console.log(result.error);
                                } 
                                else 
                                {
                                    if (result.paymentIntent.status === 'succeeded') {
                                        // The payment is complete
                                        //handle create order and save book video call to database                                 
                                        console.log(result.paymentIntent);
                                        $.ajax({
                                            type: "POST",
                                            url: custom_video_call_ajaxURL,
                                            dataType: 'json',
                                            data: 
                                            {
                                                action:'custom_video_service_complete_order',
                                                payment_intentID:result.paymentIntent.id,
                                                payment_status:result.paymentIntent.status,
                                                payment_currency_code:result.paymentIntent.currency,
                                                payment_currency_sign:$("#service_currency_sign").val(),
                                                payment_created:result.paymentIntent.created,
                                                payment_amount:$("#service_price").val(),                                                                                            
                                                service_id:$("#service_id").val(),
                                                booking_date:$("#selected_date").val(),
                                                booking_time:$("#selected_time").val(),
                                                booking_note:$("#booking_note").val(),
                                                booking_type:$("#booking_type").val(),
                                                booking_location:$("#booking_location").val(),
                                                
                                            },
                                            success: function(bookingResponse) {   
                                                    if(bookingResponse.success=='true')
                                                    {
                                                        toastr.success(bookingResponse.message);
                                                        window.location.href=bookingResponse.redirect_url;
                                                    }
                                            },
                                        });
                                       
                                    }
                                }
                            });
                        }
                                           
                    },   
                    error: function(error) {                    
                        toastr.error('Something went wrong. Please refresh !!');
                        console.log(error);
                    }             
                });
                
            });
        }
        
        if($("#service_category_selector").length > 0)
        {
                
            new TomSelect("#service_category_selector",{
                create: true,
               
            });

            new TomSelect("#service_sort_by_time",{
                create: true,
              
            });         
            
            /*new TomSelect("#service_sort_by_price",{
                create: true,
               
            });   */
            
            new TomSelect("#service_sort_by_meet_type",{
                create: true,
               
            });   

        }

        //handling search form
        if($('.service-custom-filters-container').length > 0)
        {
            let search_redirect_url='';
            let search_query='';
            let category_query='';
            //let price_query='';
            let meetType_query='';
            let date_query='';

            let only_my_service_query='';

        }
        $("#submit-search-service").click(function(){
                search_query='?search='+$("#search_string").val();
                category_query='&category='+$("#service_category_selector").val();
                date_query='&date='+$("#service_sort_by_time").val();
                //price_query='&price='+$("#service_sort_by_price").val();
                meetType_query='&meettype='+$("#service_sort_by_meet_type").val();

                if($("#only_my_service").is(":checked"))
                {
                    only_my_service_query='&myservice=yes';
                }
                else
                {
                    only_my_service_query='&myservice=no';
                }

                search_redirect_url=$("#search_link").val()+search_query+category_query+date_query+meetType_query+only_my_service_query;
                window.location.href=search_redirect_url;
        });
     

        if($('.booking-filters-area').length > 0)
        {
            let booking_filter_url='';

            let booking_sort_meettype='';
            let booking_status_filter='';
            let booking_sort_filter='';
          
        }
        $("#booking_filter_search").click(function(){
            booking_sort_meettype='?type='+$('#booking_sort_meettype').val();

            booking_status_filter='&status='+$('#booking_status_filter').val();

            booking_sort_filter='&order='+$('#booking_sort_filter').val();

            booking_filter_url=$("#booking-filter-base-link").val()+booking_sort_meettype+booking_status_filter+booking_sort_filter;
            
            window.location.href=booking_filter_url;

        });


        $(".custom_btn_publish").click(function(){            
            let publish_service_id=$(this).attr('data-service-id');
            $.ajax({
                type: "POST",
                url: custom_video_call_ajaxURL,
                dataType: 'json',
                data: 
                {
                    action:'publish_unpublish_service',
                    service_id: publish_service_id,                 
                    
                },
                success: function(response) {   
                    if(response.success=='true')
                    {
                        toastr.success(response.message);
                        window.location.reload();
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                },
            });
            
        });
           
        
        $("#disconnect-stripe-account").click(function(){

            Swal.fire({
                icon: 'warning',                           
                title:'Are you sure want to disconnect Stripe Account ?',              
                confirmButtonText:'<h3 style="padding:0px;margin:0;">Remove</h3>',    
                showCancelButton: true,         
                cancelButtonText:'<h3 style="padding:0px;margin:0;">Cancel</h3>',   
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',              
              }).then((result) => {
                if (result.isConfirmed) 
                {                
                    $.ajax({

                        type: "POST",
                        url: custom_video_call_ajaxURL,
                        dataType: 'json',
                        data: {
                            action:'disconnect_stripe_account',
                        },                       
                        success: function(response) {   
                            toastr.success(response.message);       
                            window.location.reload();                                                            
                        },   
                        error: function(error) {                    
                           toastr.error('something went wrong, please refresh');
                        }             
                    });
                }
           });
                
        });

                //cancel and refund booking
                $(".cancel-btn-class").click(function(){
                    let cancel_booking_id=$(this).attr('data-cancel-id');
                    Swal.fire({
                        icon: 'warning',                           
                        title:'Are you sure want to cancel and get refund ?', 
                        input:'text',                        
                        inputAttributes: {
                          autocapitalize: "off"
                        },
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText:'<h3 style="padding:0px;margin:0;">Confirm</h3>',    
                        showCancelButton: true,         
                        cancelButtonText:'<h3 style="padding:0px;margin:0;">Close</h3>',                 
                      }).then((result) => {
                        if (result.isConfirmed) 
                        {                
                            const reason_to_cancel = Swal.getInput().value;                      
                            $.ajax({

                                type: "POST",
                                url: custom_video_call_ajaxURL,
                                dataType: 'json',
                                data: {
                                    booking_id:cancel_booking_id,
                                    cancel_reason:reason_to_cancel,
                                    action:'cancel_booking_and_refund',
                                },      
                                beforeSend: function(){
                                    toastr.warning('Attemping to complete','',{timeOut: 5000});
                                },                     
                                success: function(response) {   
                                    if(response.success=='true')
                                    {
                                        toastr.success(response.message);       
                                        window.location.reload();     
                                    }                                                                                         
                                },   
                                error: function(error) {                    
                                   toastr.error('something went wrong, please refresh');
                                }             
                            });
                        }
        
                })
            });

            $(".complete-booking-btn").click(function(){
                let complete_booking_id=$(this).attr('data-complete-id');
                Swal.fire({
                    icon: 'warning',                           
                    title:'Are you sure want to complete booking ?',                                                          
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText:'<h3 style="padding:0px;margin:0;">Confirm</h3>',    
                    showCancelButton: true,         
                    cancelButtonText:'<h3 style="padding:0px;margin:0;">Close</h3>',                 
                  }).then((result) => {
                    if (result.isConfirmed) 
                    {                                        
                        $.ajax({

                            type: "POST",
                            url: custom_video_call_ajaxURL,
                            dataType: 'json',
                            data: {
                                booking_id:complete_booking_id,                                
                                action:'stripe_transfer_complete_payment',
                            },   
                            beforeSend: function(){
                                toastr.warning('Attemping to complete','',{timeOut: 5000});
                            },                 
                            success: function(response) {   
                                if(response.success=='true')
                                {
                                    toastr.success(response.message);       
                                    window.location.reload();     
                                }                                                                                         
                            },   
                            error: function(error) {                    
                               toastr.error('something went wrong, please refresh');
                            }             
                        });
                    }
    
            })
            });

    })
})(jQuery);