(function ($) {
    $(document).ready(function () {                  
        const roomID = video_call_room_id;
        const appID=zegocloud_appid;
        //const serverSecret = zegocloud_secret;
        const userName = zegocloud_display_name;
       //const userID = Math.floor(Math.random() * 10000) + "";
        const userID=zegocloud_create_room_user_id;        
        let zego_token='';
        

        //const kitToken =  ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, roomID, userID, userName);
        async function fetchData() {
      await fetch(custom_ajax_action_url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            zego_token=data.token.token;
            console.log(data.token.token);
            const kitToken =  ZegoUIKitPrebuilt.generateKitTokenForProduction(appID, data.token.token, roomID, userID, userName);
            const zp = ZegoUIKitPrebuilt.create(kitToken);

            if($("#video-container").length > 0)
                {   
                    zp.joinRoom({
                        container: document.querySelector("#video-container"),
                       /* sharedLinks: [{
                            url: window.location.protocol + '//' + window.location.host + window.location.pathname + '?roomID=' + roomID,
                        }],*/
                        scenario: {
                            mode: ZegoUIKitPrebuilt.VideoConference,
                        },
                        turnOnMicrophoneWhenJoining: true,
                        turnOnCameraWhenJoining: true,
                        showMyCameraToggleButton: true,
                        showMyMicrophoneToggleButton: true,
                        showAudioVideoSettingsButton: false,
                        showScreenSharingButton: false,
                        showTextChat: true,
                        showUserList: false,
                        maxUsers: 2,
                        layout: "Auto",
                        showLayoutButton: false,
                
                    });
                }

                
        });        
    }
    fetchData();

        

        
       
    });
})(jQuery);
