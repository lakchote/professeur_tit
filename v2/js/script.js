$(function() {
    "use strict";

    /*http://www.design-fluide.com/17-11-2013/un-defilement-anime-smooth-scroll-en-jquery-sans-plugin/*/
    $('.js-scrollTo').on('click', function() { // Au clic sur un élément
        var page = $(this).attr('href'); // Page cible
        var speed = 750; // Durée de l'animation (en ms)
        $('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
        return false;
    });
    var $tailleEcran = $( window ).width();
    carres($tailleEcran);
    parrotVideo($tailleEcran);
    $(window).on('resize', function () {

        $tailleEcran = $(this).width();

        carres($tailleEcran);
        parrotVideo($tailleEcran);
    });

});

/*
* Function carres dispatches pictures and text in the rigght position depending on screen width
*/

function carres($taille) {
    $('#fourth_carre1').empty();
    $('#fourth_carre2').empty();
    $('#fourth_carre1').removeClass('oiseau_fruit');
    $('#fourth_carre2').removeClass('oiseau_fruit');
    if ($taille <= 767) {
        $('#fourth_carre1').addClass('oiseau_fruit');
        $('#fourth_carre1').html('<p class="white carre">CONTRIBUER A SON ECOSYSTEME</p>');
        $('#fourth_carre2').html('<img src=\"./img/png/discuter.png\" alt=\"Discuter\" class="howCircle"> <p class="square_black_text">RENCONTRER ET DISCUTER<br/>AVEC DES PROFESSIONNELS</p>');
    }
    else {
        $('#fourth_carre2').addClass('oiseau_fruit');
        $('#fourth_carre2').html('<p class="white carre">CONTRIBUER A SON ECOSYSTEME</p>');
        $('#fourth_carre1').html('<img src=\"./img/png/discuter.png\" alt=\"Discuter\" class="howCircle">  <p class="square_black_text">RENCONTRER ET DISCUTER<br/>AVEC DES PROFESSIONNELS</p>');
    }
}

/*
 * Function carres dispatches pictures and text in the rigght position depending on screen width
 */

function parrotVideo($taille) {

    if ($taille > 767) {
        if (!$('#introVideo').length) {
            $('#videoContent').prepend('<video id="introVideo" src="./video/parrot-TIT.mp4" loop="true" muted="true" autoplay="true"></video>');
            //If Javascript is allowed, show content and screen size requires video content
                $('#videoContent').css('background', 'transparent');
        }
    }
    else {
        if ($('#introVideo').length) {
            $('#introVideo').remove();

        }
    }
}
