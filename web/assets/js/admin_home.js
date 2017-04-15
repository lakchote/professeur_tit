$(function()
{
    $('.toggleHiddenLinks').click(function(e)
    {
        e.preventDefault();
       var elem = this.parentNode.parentNode.childNodes[1];
       $(elem).show('350');
    });
});
