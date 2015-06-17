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
    streamPublish("Concours photo tatouage", "J'ai participé au concours photo de Tatoo Nous ! Allez voir ma photo sur la page de Tatoo Nous !", 'Concours photo Tatoo Nous', 'https://www.facebook.com/pages/Tatoo-nous/1404451319873347', "Demo Tatoo Nous");
}

function publishFeedConcours()
{
    var body = 'Reading JS SDK documentation';
    FB.api('/me/feed', 'post', { message: body }, function(response) {
      if (!response || response.error) {
        alert('Error occured');
      } else {
        alert('Post ID: ' + response.id);
      }
    });
}