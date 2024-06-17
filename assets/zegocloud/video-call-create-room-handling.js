(function ($) {
    $(document).ready(function () {   
    
function getUrlParams(
    url  = window.location.href
  ) {
    let urlStr = url.split('?')[1];
    return new URLSearchParams(urlStr);
  }
    var script = document.createElement("script");
    script.type = "text/javascript";
  
    script.addEventListener("load", function (event) {
      const roomID = getUrlParams().get('roomID') || Math.floor(Math.random() * 10000) + "";
      const userID = Math.floor(Math.random() * 10000) + "";
     // const userName = "userName" + userID;
     // const appID =  1771199126;
     // const serverSecret = "64dade68167d66106af778e1789f3377";
        const appID=zegocloud_appid;
        const serverSecret = zegocloud_secret;
        const userName = zegocloud_display_name;
      
       // Generate a Kit Token by calling a method.
      const kitToken =  ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, roomID, userID, userName);
    
      const zp = ZegoUIKitPrebuilt.create(kitToken);
     
      zp.joinRoom({
            
          container: document.querySelector("#video-container"),
          sharedLinks: [{
              url: window.location.protocol + '//' + window.location.host + window.location.pathname + '?roomID=' + roomID,
          }],
          scenario: {
              mode: ZegoUIKitPrebuilt.VideoConference,
          },
          onUserAvatarSetter:(userList) => {
            userList.forEach(user => {
                user.setUserAvatar("http://fabien.et/wp-content/uploads/2024/05/sample_avatar.jpg")
            })
        }, 
  
      });
    });
  
    script.src = "https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js";
    document.getElementsByTagName("head")[0].appendChild(script);
  
    });
})(jQuery);
