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

function validateForm() {
    var errors = [];
    
    if (!document.getElementById('photoName') || document.getElementById('photoName').value.length == 0) {
        console.log('VIDE');
        errors.push("Vous devez remplir tous les champs du formulaire.");
    }
    
    if (!document.getElementById('form_policy') || !document.getElementById('form_policy').checked) {
        errors.push("Vous devez accepter le règlement pour pouvoir participer au concours.");
    }
    
    if (!document.getElementById('fb-photo-id') || document.getElementById('fb-photo-id').value.length == 0) {
        errors.push("Veuillez sélectionner un fichier à envoyer.");
    }
    
    if (errors.length > 0) {
        var errorsString = "";
        
        for (var i = 0, l=errors.length; i<l;i++) {
            errorsString += "<span>"+errors[i]+"</span><br>";
        }
        
        document.getElementById('participate-end-errors-form').innerHTML = errorsString + document.getElementById('participate-end-errors-form').innerHTML;
        
        return false;
    } else {
        publishFeedConcours();
        
        return true;
    }
}

function publishFeedConcours()
{
    var body = 'J\'ai participé au concours photo de Tatoo Nous ! Allez voir ma photo sur la page de Tatoo Nous ! https://www.facebook.com/pages/Tatoo-nous/1404451319873347';
    
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            FB.api('/me/feed', 'post', { message: body }, function(response) {
                if (!response || response.error) {
                } else {
                }
            });
        } else if (response.status === 'not_authorized') {
        } else {
        }
   });
}