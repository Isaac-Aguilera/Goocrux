/*
    Elimina un video indicando su id.
*/
function eliminarvideo(id, token) {
    $('#modal' + id).modal('toggle');
    /*
        Lanza una peticion ajax con el id del video que quiere eliminar.
    */
    $.ajax({
        url: '/deletevid',
        method: 'delete',
        data: {
            '_token': token,
            'id': id,
        },
        error: function (response) {
            /*
                Si la peticion ajax terorna error lanza un pop up comunicando el error.
            */
            var alertDiv = `<div class="modal fade" id="modal0">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-bold">Delete Error</h5>
                                    <button type="button" class="close" data-dismiss="modal0" aria-label="Close">
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
            $('#modal0').modal('toggle');
        },
        success: function (response) {
            /*
                Si la peticion ajax funciona correctamente elimina de la vista el video eliminado.
            */
            document.getElementById(id).remove()
        }
    });
}