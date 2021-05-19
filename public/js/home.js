/*
    Cambia el listado de videos y los lista segun la categoria indicada.
*/
function cambiarCategoria(id, token) {
    /*
        Lanza una peticion ajax con el id de la categoria que quiere mostrar.
    */
    $.ajax({
        url: '/cambiarCategoria',
        method: 'post',
        data: {
            '_token': token,
            'id': id
        },
        error: function (response) {
            /*
                Si la peticion ajax terorna error lanza un pop up comunicando el error.
            */
            var alertDiv = `<div class="modal fade" id="modal">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Category Error</h5>
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
                Si la peticion ajax funciona correctamente lista todos los videos de esa categoria y muestra la categoria.
            */
            document.getElementById('categoria').innerHTML = response['name'];
            document.getElementById('videos').innerHTML = response['content'];
        }
    });

}
