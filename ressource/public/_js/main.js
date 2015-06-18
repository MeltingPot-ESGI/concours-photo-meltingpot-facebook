window.fbAsyncInit = function() {
    FB.init({
      appId      : '342576715932172',
      cookie: true,
      xfbml      : true,
      oauth: true,
      version    : "v2.3"
    });
    };

    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/fr_FR/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));

function streamPublish(name, description, hrefTitle, hrefLink, userPrompt)
{
    FB.ui(
    {
        method: 'feed',
        message: '',
        attachment: {
            name: name,
            caption: '',
            description: (description),
            href: hrefLink
        },
        action_links: [
            { text: hrefTitle, href: hrefLink }
        ],
        user_prompt_message: userPrompt
    },
    function(response) {
        console.log(response);
    });
}

function publishStreamConcours()
{
    streamPublish("Concours photo tatouage", "", 'Concours photo Tatoo Nous', '', "Demo Tatoo Nous");
}

function publishFeedConcours()
{
    var body = 'J\'ai participé au concours photo de Tatoo Nous ! Allez voir ma photo sur la page de Tatoo Nous ! https://www.facebook.com/pages/Tatoo-nous/1404451319873347';
    
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            FB.api('/me/feed', 'post', { message: body }, function(response) {
            if (!response || response.error) {
              console.log(response);
            } else {
              console.log('Post ID: ' + response.id);
            }
          });
          // the user is logged in and has authenticated your
          // app, and response.authResponse supplies
          // the user's ID, a valid access token, a signed
          // request, and the time the access token 
          // and signed request each expire
          var uid = response.authResponse.userID;
          var accessToken = response.authResponse.accessToken;
        } else if (response.status === 'not_authorized') {
          // the user is logged in to Facebook, 
          // but has not authenticated your app
        } else {
          // the user isn't logged in to Facebook.
        }
   });
}