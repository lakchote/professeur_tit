(function () {
    'use strict';

    //If Javascript is allowed, show content
    document.getElementById('intro-visual-content').classList.remove('hide');
    //document.getElementById('app-cta').classList.remove('hide');

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
}());

$(function () {
    $('#carousel').swiperight(function () {
        $(this).carousel('prev');
    });
    $('#carousel').swipeleft(function () {
        $(this).carousel('next');
    });
});