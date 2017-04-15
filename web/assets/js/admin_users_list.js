$(function () {
    $('.user__unban').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url : url,
            method: 'GET'
        }).done(function ()
        {
          location.reload();
        })
    });
    $('.user__delete').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url : url,
            method: 'GET'
        }).done(function ()
        {
            location.reload();
        })
    });
    $('.naturaliste__valid').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url : url,
            method: 'GET'
        }).done(function ()
        {
            location.reload();
        })
    });
    $('.naturaliste__invalid').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url : url,
            method: 'GET'
        }).done(function ()
        {
            location.reload();
        })
    });
    $('.modal__ban--load').click(function(e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $('#modal__ban').modal();
        $('#modal__ban--content').html('Chargement...');
        $.ajax
        ({
            'url' : url,
            'method' : 'GET'
        }).done(function(data)
        {
            $('#modal__ban--content').html(data);
        }).error(function()
        {
            alert('Une erreur est survenue lors du chargement de la modale.')
        });
    });
    $('.modal__changePassword--load').click(function(e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $('#modal__changePassword').modal();
        $('#modal__changePassword--content').html('Chargement...');
        $.ajax
        ({
            'url' : url,
            'method' : 'GET'
        }).done(function(data)
        {
            $('#modal__changePassword--content').html(data);
        }).error(function()
        {
            alert('Une erreur est survenue lors du chargement de la modale.')
        });
    });
});
