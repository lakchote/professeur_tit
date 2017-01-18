(function () {
    'use strict';

    //If Javascript is allowed, show content
    document.getElementById('intro-visual-content').classList.remove('hide');
    document.getElementById('app-cta').classList.remove('hide');

    /**
     * Check if not on mobile and adds a video instead of an image
     */
    if (window.innerWidth > 640) {
        var video = document.createElement('video');
        video.id = 'intro-video';
        video.src = './assets/blue_tit.mp4';
        video.setAttribute('loop', true);
        video.setAttribute('muted', true);
        video.setAttribute('autoplay', true);
        document.getElementById('video-container').appendChild(video);
        video.addEventListener('loadstart', function () {
            document.getElementById('intro-visual-slogan').classList.add('loader-text-color');
            document.getElementById('intro-visual-timer').classList.add('loader-text-color');
            document.getElementById('intro-visual-loader').classList.remove('hide');
            document.getElementById('intro-visual-loader').classList.add('loader');
        });
        video.addEventListener('canplaythrough', function () {
            document.getElementById('intro-visual-loader').classList.remove('loader');
            document.getElementById('intro-visual-timer').classList.remove('loader-text-color');
            document.getElementById('intro-visual-slogan').classList.remove('loader-text-color');
        });
    }

    /**
     * Timer
     * @type {Date}
     */
    var releaseDate = new Date(2017, 2, 28);
    var today = new Date();
    //If today's date is in February, show remaining hours until launch (at 10.00 am)
    if (today.getMonth() == 1) {
        document.getElementById('intro-timer-month').classList.add('hide');
        document.getElementById('intro-timer-days').classList.add('hide');
        document.getElementById('intro-timer-hours').classList.remove('hide');
        document.getElementById('intro-timer-hours').textContent = ( (releaseDate.getDate() - today.getDate()) * 24 + 10) + ' heures';
    }
    // Else, calculate month and days remaining
    else {
        var totalDaysRemaining = (31 - today.getDate()) + 28;
        if (totalDaysRemaining / 31 > 1) {
            document.getElementById('intro-timer-month').textContent = '1 mois';
            document.getElementById('intro-timer-days').textContent = releaseDate.getDate() - today.getDate() + ' jours';
        }
        else {
            document.getElementById('intro-timer-month').classList.add('hide');
            document.getElementById('intro-timer-days').textContent = totalDaysRemaining + ' jours';
        }
    }


    /**
     * Adds user's email to MailChimp account and shows validation message
     * Prevents addition of the same mail
     * @param {string} htmlDOMElement - the DOM Element's content to replace
     * @param {string} userEmail
     * @param {string} color - color of the text
     * @param {string} margin - bottom margin for the text
     */
    function addToMailChimp(htmlDOMElement, userEmail, color, margin) {
        if(!document.getElementById('cta-true')) {
            var url = 'https://us14.api.mailchimp.com/2.0/lists/subscribe.json?apikey=9d1b0a206258b3dc70c7090fb792c89c-us14&id=0e2fad9074&email[email]=' + userEmail +
                '&double_optin=false&send_welcome=false';
            var httpRequest = new XMLHttpRequest();
            httpRequest.open("GET", url, true);
            httpRequest.send();
            document.getElementById(htmlDOMElement).innerHTML = '<div class="col-xs-12 text-center" id="cta-true" style="font-size:1.3em; color:' + color + '; margin-bottom:' + margin + ' ;">Vous êtes bien inscrits sur la liste.</div>';
        }
        else {
            showModal('Erreur', 'Vous vous êtes déjà inscrit.');
        }
    }

    /**
     * Shows Bootstrap's modal to the user
     * @param {string} title - Title of the modal
     * @param {string} content - Content of the modal
     */
    function showModal(title, content) {
        document.getElementById('modal-title-text').textContent = title;
        document.getElementById('modal-body-text').textContent = content;
        $('#modal-errors').modal('show');
    }

    document.getElementById('intro-mail-button').addEventListener('click', function () {
        var userEmail = document.getElementById('intro-mail-input').value;
        (userEmail) ? addToMailChimp('intro-cta-container', userEmail, 'yellow', '100px') : showModal('Erreur', 'Vous devez spécifier votre mail.');
    });
    document.getElementById('app-cta-button').addEventListener('click', function () {
        var userEmail = document.getElementById('app-cta-userinput').value;
        (userEmail) ? addToMailChimp('app-cta-container', userEmail, 'green', '20px') : showModal('Erreur', 'Vous devez spécifier votre mail.');
    });

}());