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
        $.ajax
        ({
            'url' : url,
            'method' : 'GET'
        }).done(function(data)
        {
            $('#modal__ban--content').html(data);
            $('#modal__ban').modal();
        }).error(function()
        {
            alert('Une erreur est survenue lors du chargement de la modale.')
        });
    });
    $('.modal__changePassword--load').click(function(e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            'url' : url,
            'method' : 'GET'
        }).done(function(data)
        {
            $('#modal__changePassword--content').html(data);
            $('#modal__changePassword').modal();
        }).error(function()
        {
            alert('Une erreur est survenue lors du chargement de la modale.')
        });
    });
});
