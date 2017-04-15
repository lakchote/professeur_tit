$(function () {
    $('#obs__content').DataTable
    ({
        "language":
            {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/French.json"
            }
    });
    $('.modal__obs--load').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $('#modal__obs').modal();
        $('#modal__obs--content').html('Chargement...');
        $.ajax
        ({
            url : url,
            method : 'GET'
        }).done(function (data) {
            $('#modal__obs--content').html(data);
        }).error(function()
        {
            alert('Une erreur est survenue lors du chargement de la modale.')
        });
    });
    $('.obs__delete').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url: url,
            method: 'GET'
        }).done(function ()
        {
            location.reload();
        });
    });
    $('.obs__invalid').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url: url,
            method: 'GET'
        }).done(function ()
        {
            location.reload();
        });
    });
    $('.obs__valid').click(function (e)
    {
        e.preventDefault();
        var url = this.getAttribute('data-target');
        $.ajax
        ({
            url: url,
            method: 'GET'
        }).done(function ()
        {
            location.reload();
        });
    });
});
