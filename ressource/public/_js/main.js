function streamPublish(name, description, hrefTitle, hrefLink, userPrompt)
{
    FB.ui(
    {
        method: 'stream.publish',
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
    streamPublish("Concours photo tatouage", "J'ai participé au concours photo de Tatoo Nous ! Allez voir ma photo sur la page de Tatoo Nous !", 'Concours photo Tatoo Nous', 'www.tatoo-nous.com', "Demo Tatoo Nous");
}