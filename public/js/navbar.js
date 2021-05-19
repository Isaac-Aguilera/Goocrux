/*
    Pone las notificaciones a 0.
*/
function netejarnoti(token) {
    /*
        Lanza una peticion ajax para desactivar las notificaciones activas.
    */
    $.ajax({
        url: '/netejarnoti',
        method: 'POST',
        data: {
            '_token': token,
        },
        error: function (response) {
            /*
                Si la peticion ajax terorna error lanza un pop up comunicando el error.
            */
            var alertDiv = `<div class="modal fade" id="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-bold">Notification Error</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>` + response['statusText'] + `</p>
                                </div>
                            </div>
                        </div>
                    </div>`;
            document.getElementById("container").innerHTML += alertDiv;
            $('#modal').modal('toggle');
        },
        success: function (response) {
            /*
                Si la peticion ajax funciona correctamente pone las notificaciones a 0.
            */
            document.getElementById("notinumber").innerHTML = "0";
        }
    });
}

/*
    Boton para hacer scroll al inicio de la pagina.
*/
$(document).ready(function () {
    /*
        Si se ha bajado mas de 50 px se activa el boton.
    */
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    
    /*
        Hace scroll al inicio de la pagina.
    */
    $('#back-to-top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
});