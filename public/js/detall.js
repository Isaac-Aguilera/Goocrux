/*
    Muestra un textarea y un boton para poder modificar el comentario del usuario.
*/
function editarComentari(id, contingut, token) {
    document.getElementById((id + '_contingut').toString()).innerHTML =
        '<textarea name="contingut" id="' + id + '_area" class="form-control mt-3" rows="5">' + contingut + '</textarea>' +
        '<button onclick="confirmarEditarComentari(' + id + ', \'' + token + '\')" class="btn btn-large btn-block btn-primary mt-3">Confirm</button>';
}

/*
    Cambia el contenido del comentario del usuario.
*/
function confirmarEditarComentari(id, token) {
    contingut = document.getElementById((id + '_area').toString()).value;
    /*
        Lanza una peticion ajax con el nuevo contenido y el id del comentario.
    */
    $.ajax({
        url: '/editarComentari',
        method: 'post',
        data: {
            '_token': token,
            'contingut': contingut,
            'id': id,
        },
        error: function (response) {
            /*
                Si la peticion ajax terorna error lanza un pop up comunicando el error.
            */
                var alertDiv = `<div class="modal fade" id="modal">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Comment Error</h5>
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
                Si la peticion ajax funciona correctamente se muestra el nuevo contenido del comentario.
            */
            document.getElementById((id + '_contingut').toString()).innerHTML = '<div id=\'' + response['id'] + '_contingut\'>' +
                '<p class="mt-2 ml-5">' + contingut + '</p>' +
                '</div>';
        }
    });
}

/*
    Elimina un comentario.
*/
function eliminarComentari(id, token) {
    /*
        Lanza una peticion ajax con el id del comentario.
    */
    $.ajax({
        url: '/comentari',
        method: 'delete',
        data: {
            '_token': token,
            'id': id,
        },
        error: function (response) {
            /*
                Si la peticion ajax terorna error lanza un pop up comunicando el error.
            */
            var alertDiv = `<div class="modal fade" id="modal">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Comment Error</h5>
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
                Si la peticion ajax funciona correctamente se muestra el nuevo contenido del comentario.
            */
            if (!response['comentaris']) {
                document.getElementById('comentaris').innerHTML += '<h5>There are no comments!</h5>';
            }
            document.getElementById('contador').innerHTML = response['comentaris'] + " comments";
            document.getElementById(id).remove();
        }
    });
}

/*
    Crea un comentario.
*/
function afegirComentari(video_id, token) {
    contingut = document.getElementById('contingut').value;
    document.getElementById('contingut').value = "";

    /*
        Lanza una peticion ajax con el contenido del comentario y el id del video.
    */
    $.ajax({
        url: '/comentari',
        method: 'post',
        data: {
            '_token': token,
            'video_id': video_id,
            'contingut': contingut
        },
        error: function (response) {
            /*
                Si la peticion ajax terorna error lanza un pop up comunicando el error.
            */
            if (response['statusText'] == "Unprocessable Entity") {
                var alertDiv = `<div class="modal fade"  id="modal2">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Comment Error</h5>
                    <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                    </div>
                    <div class="modal-body">
                    <p>You have to write something to comment!</p>
                    </div>
                </div>
                </div>
            </div>`;
                document.getElementById("container").innerHTML += alertDiv;
                $('#modal2').modal('toggle');
            } else if (response['statusText'] == "Unauthorized") {
                var alertDiv = `<div class="modal fade"  id="modal2">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Comment Error</h5>
                    <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                    </div>
                    <div class="modal-body">
                    <p>You have to login to comment!</p>
                    </div>
                </div>
                </div>
            </div>`;
                document.getElementById("container").innerHTML += alertDiv;
                $('#modal2').modal('toggle');
            } else {
                var alertDiv = `<div class="modal fade" id="modal">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Comment Error</h5>
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
            }
        },
        success: function (response) {
            /*
                Si la peticion ajax funciona correctamente se muestra el nuevo comentario.
            */
            afegir = `<div id=` + response['id'] + `>
                <a href="/user/` + response['nick'] + `">
                    <img class="mr-1"style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"src="/` + response['image'] + `">
                </a>
                <span>` + response['nick'] + `</span>
                <div class="dropdown float-right">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button onclick="editarComentari(` + response['id'] + `, '` + contingut + `', '` + token + `')" class="dropdown-item" >Edit</button>
                        <button onclick="eliminarComentari(` + response['id'] + `, '` + token + `')" class="dropdown-item" >Delete</button>
                    </div>
                </div>
                <div id='` + response['id'] + `_contingut'>
                    <p class="mt-2 ml-5">` + contingut + `</p>
                </div>
            </div>`;
            /*
                Segun los comentarios que hayan se añade o se pone ese para quitar el aviso "There are no comments!".
            */
            if (response['comentaris'] == 1) {
                document.getElementById('comentaris').innerHTML = afegir;
            } else {
                document.getElementById('comentaris').innerHTML += afegir;
            }

            /*
                Actualiza el numero de comentarios.
            */
            document.getElementById('contador').innerHTML = response['comentaris'] + " comments";

        }
    });
}

/*
    Crea un voto de like.
*/
function like(id, votacio, token) {
    /*
        Comprueba si debe hacer like o dislike.
    */
    if (votacio == 'like') {
        /*
            Comprueba si el like esta marcado o no para crear o eliminar el voto.
        */
        if (document.getElementById("like_" + id).className == "bi bi-hand-thumbs-up") {
            /*
                Lanza una peticion ajax con la votacion "like" y el id del video para crear el voto.
            */
            $.ajax({
                url: '/vot',
                method: 'post',
                data: {
                    '_token': token,
                    'id': id,
                    'votacio': votacio
                },
                error: function (response) {
                    /*
                        Si la peticion ajax terorna error lanza un pop up comunicando el error.
                    */
                    if (response['statusText'] == "Unauthorized") {
                        var alertDiv = `<div class="modal fade" id="modal3">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
                            <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            </div>
                            <div class="modal-body">
                            <p>You have to login to vote!</p>
                            </div>
                        </div>
                        </div>
                    </div>`;
                        document.getElementById("container").innerHTML += alertDiv;
                        $('#modal3').modal('toggle');
                    } else {
                        var alertDiv = `<div class="modal fade" id="modal">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
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
                    }
                },
                success: function (response) {
                    /*
                        Si la peticion ajax funciona correctamente se cambian los iconos de like y\o dislike.
                    */
                    document.getElementById("dislike_" + id + "_count").innerHTML = response['dislikes'];
                    document.getElementById("dislike_" + id).className = "bi bi-hand-thumbs-down";
                    document.getElementById("like_" + id + "_count").innerHTML = response['likes'];
                    document.getElementById("like_" + id).className = "bi bi-hand-thumbs-up-fill";
                }
            });
        } else {
            /*
                Lanza una peticion ajax con la votacion "like" y el id del video para elminiar el voto.
            */
            $.ajax({
                url: '../vot',
                method: 'delete',
                data: {
                    '_token': token,
                    'id': id
                },
                error: function (response) {
                    /*
                        Si la peticion ajax terorna error lanza un pop up comunicando el error.
                    */
                    if (response['statusText'] == "Unauthorized") {
                        var alertDiv = `<div class="modal fade" id="modal3">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
                            <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            </div>
                            <div class="modal-body">
                            <p>You have to login to vote!</p>
                            </div>
                        </div>
                        </div>
                    </div>`;
                        document.getElementById("container").innerHTML += alertDiv;
                        $('#modal3').modal('toggle');
                    } else {
                        var alertDiv = `<div class="modal fade" id="modal">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
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
                    }
                },
                success: function (response) {
                    /*
                        Si la peticion ajax funciona correctamente se cambia el icono de like.
                    */
                    document.getElementById("like_" + id + "_count").innerHTML = response['likes'];
                    document.getElementById("like_" + id).className = "bi bi-hand-thumbs-up";
                }
            });
        }
    } else {
        /*
            Comprueba si el dislike esta marcado o no para crear o eliminar el voto.
        */
        if (document.getElementById("dislike_" + id).className == "bi bi-hand-thumbs-down") {
            /*
                Lanza una peticion ajax con la votacion "dislike" y el id del video para crear el voto.
            */
            $.ajax({
                url: '../vot',
                method: 'post',
                data: {
                    '_token': token,
                    'id': id,
                    'votacio': votacio
                },
                error: function (response) {
                    /*
                        Si la peticion ajax terorna error lanza un pop up comunicando el error.
                    */
                    if (response['statusText'] == "Unauthorized") {
                        var alertDiv = `<div class="modal fade" id="modal3">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
                            <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            </div>
                            <div class="modal-body">
                            <p>You have to login to vote!</p>
                            </div>
                        </div>
                        </div>
                    </div>`;
                        document.getElementById("container").innerHTML += alertDiv;
                        $('#modal3').modal('toggle');
                    } else {
                        var alertDiv = `<div class="modal fade" id="modal">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
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
                    }
                },
                success: function (response) {
                    /*
                        Si la peticion ajax funciona correctamente se cambian los iconos de like y\o dislike.
                    */
                    document.getElementById("like_" + id + "_count").innerHTML = response['likes'];
                    document.getElementById("like_" + id).className = "bi bi-hand-thumbs-up";
                    document.getElementById("dislike_" + id + "_count").innerHTML = response['dislikes'];
                    document.getElementById("dislike_" + id).className = "bi bi-hand-thumbs-down-fill";
                }
            });
        } else {
            /*
                Lanza una peticion ajax con la votacion "dislike" y el id del video para elminiar el voto.
            */
            $.ajax({
                url: '../vot',
                method: 'delete',
                data: {
                    '_token': token,
                    'id': id
                },
                error: function (response) {
                    /*
                        Si la peticion ajax terorna error lanza un pop up comunicando el error.
                    */
                    if (response['statusText'] == "Unauthorized") {
                        var alertDiv = `<div class="modal fade" id="modal3">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
                            <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            </div>
                            <div class="modal-body">
                            <p>You have to login to vote!</p>
                            </div>
                        </div>
                        </div>
                    </div>`;
                        document.getElementById("container").innerHTML += alertDiv;
                        $('#modal3').modal('toggle');
                    } else {
                        var alertDiv = `<div class="modal fade" id="modal">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Vote Error</h5>
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
                    }
                },
                success: function (response) {
                    /*
                        Si la peticion ajax funciona correctamente se cambia el icono de dislike.
                    */
                    document.getElementById("dislike_" + id + "_count").innerHTML = response['dislikes'];
                    document.getElementById("dislike_" + id).className = "bi bi-hand-thumbs-down";
                }
            });
        }
    }
}

/*
    Valoracion.
*/
function valorar(name, id, video_id, token) {
    /*
        Comprueba si tiene que eliminar o crear la valoracion, si la estrella que ha clickado 
        es la ultima que esta amarilla se elimina la valoracion. En caso contrario se crea o edita.
    */
    if (document.getElementById(name + (id).toString()).classList.contains('perma') && (id + 1 == 6 || !document.getElementById(name + (id + 1).toString()).classList.contains('perma'))) {
        /*
            Lanza una peticion ajax con el id del video y el tipo de valoracion que quiere eliminar.
        */
        $.ajax({
            url: '/valoracio',
            method: 'delete',
            data: {
                '_token': token,
                'video_id': video_id,
                'name': name
            },
            error: function (response) {
                /*
                    Si la peticion ajax retorna error lanza un pop up comunicando el error.
                */
                if (response['statusText'] == "Unauthorized") {
                    var alertDiv = `<div class="modal fade" id="modal">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Valorate Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                        </div>
                        <div class="modal-body">
                        <p>You have to login to valorate!</p>
                        </div>
                    </div>
                    </div>
                </div>`;
                    document.getElementById("container").innerHTML += alertDiv;
                    $('#modal').modal('toggle');
                } else {
                    var alertDiv = `<div class="modal fade" id="modal">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Valorate Error</h5>
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
                }
            },
            success: function (response) {
                /*
                    Si la peticion ajax funciona correctamente cambia la valoracion media.
                */
                Object.entries(response['mitjanes']).forEach(([key, value]) => {
                    document.getElementById(key).innerHTML = '<p class="text-muted" id="' + key + '">' +
                        'The average rating is: <strong>' + value + '</strong>' +
                        '<span style="color: orange;" class="ml-1 fa fa-star pl-0 d-inline"></span>' +
                        '</p>';
                });
                /*
                    Pone las estrellas oscuras.
                */
                document.getElementById(name + (1).toString()).classList.remove('perma');
                document.getElementById(name + (2).toString()).classList.remove('perma');
                document.getElementById(name + (3).toString()).classList.remove('perma');
                document.getElementById(name + (4).toString()).classList.remove('perma');
                document.getElementById(name + (5).toString()).classList.remove('perma');
            }
        });
    } else {
        /*
            Lanza una peticion ajax con el id del video, el tipo de valoracion y su valor.
        */
        $.ajax({
            url: '../valoracio',
            method: 'post',
            data: {
                '_token': token,
                'video_id': video_id,
                'votacio': id,
                'name': name
            },
            error: function (response) {
                /*
                    Si la peticion ajax retorna error lanza un pop up comunicando el error.
                */
                if (response['statusText'] == "Unauthorized") {
                    var alertDiv = `<div class="modal fade" id="modal">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Valorate Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                        </div>
                        <div class="modal-body">
                        <p>You have to login to valorate!</p>
                        </div>
                    </div>
                    </div>
                </div>`;
                    document.getElementById("container").innerHTML += alertDiv;
                    $('#modal').modal('toggle');
                } else {
                    var alertDiv = `<div class="modal fade" id="modal">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Valorate Error</h5>
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
                }
            },
            success: function (response) {
                /*
                    Si la peticion ajax funciona correctamente cambia la valoracion media y llama a la funcion perma() 
                    para poner las estrellas necesarias en amarillo.
                */
                Object.entries(response['mitjanes']).forEach(([key, value]) => {
                    document.getElementById(key).innerHTML = '<p class="text-muted" id="' + key + '">' +
                        'The average rating is: <strong>' + value + '</strong>' +
                        '<span style="color: orange;" class="ml-1 fa fa-star pl-0 d-inline"></span>' +
                        '</p>';
                });
                perma(name, id);
            }
        });
    }
}

/*
    Cambia el color a amarillo de la estrella indicada y las anteriores.
*/
function cmbst(name, id) {

    if (id == 1) {
        document.getElementById(name + id.toString()).classList.add('checked');
    }
    if (id == 2) {
        document.getElementById(name + (id - 1).toString()).classList.add('checked');
        document.getElementById(name + id.toString()).classList.add('checked');
    }
    if (id == 3) {
        document.getElementById(name + (id - 2).toString()).classList.add('checked');
        document.getElementById(name + (id - 1).toString()).classList.add('checked');
        document.getElementById(name + id.toString()).classList.add('checked');
    }
    if (id == 4) {
        document.getElementById(name + (id - 3).toString()).classList.add('checked');
        document.getElementById(name + (id - 2).toString()).classList.add('checked');
        document.getElementById(name + (id - 1).toString()).classList.add('checked');
        document.getElementById(name + id.toString()).classList.add('checked');
    }
    if (id == 5) {
        document.getElementById(name + (id - 4).toString()).classList.add('checked');
        document.getElementById(name + (id - 3).toString()).classList.add('checked');
        document.getElementById(name + (id - 2).toString()).classList.add('checked');
        document.getElementById(name + (id - 1).toString()).classList.add('checked');
        document.getElementById(name + id.toString()).classList.add('checked');
    }

}

/*
    Cambia el color a negro de la estrella indicada y las anteriores.
*/
function cmbst2(name, id) {
    if (id == 1) {
        document.getElementById(name + id.toString()).classList.remove('checked');
    }

    if (id == 2) {
        document.getElementById(name + (id - 1).toString()).classList.remove('checked');
        document.getElementById(name + id.toString()).classList.remove('checked');
    }
    if (id == 3) {
        document.getElementById(name + (id - 2).toString()).classList.remove('checked');
        document.getElementById(name + (id - 1).toString()).classList.remove('checked');
        document.getElementById(name + id.toString()).classList.remove('checked');
    }
    if (id == 4) {
        document.getElementById(name + (id - 3).toString()).classList.remove('checked');
        document.getElementById(name + (id - 2).toString()).classList.remove('checked');
        document.getElementById(name + (id - 1).toString()).classList.remove('checked');
        document.getElementById(name + id.toString()).classList.remove('checked');
    }
    if (id == 5) {
        document.getElementById(name + (id - 4).toString()).classList.remove('checked');
        document.getElementById(name + (id - 3).toString()).classList.remove('checked');
        document.getElementById(name + (id - 2).toString()).classList.remove('checked');
        document.getElementById(name + (id - 1).toString()).classList.remove('checked');
        document.getElementById(name + id.toString()).classList.remove('checked');
    }
}

/*
    Cambia la clase de las estrellas para que no cambien o no de color dependiendo de la valoracion "id" que se introduzca.
*/
function perma(name, id) {
    if (id == 1) {
        document.getElementById(name + id.toString()).classList.add('perma');
        document.getElementById(name + (id + 1).toString()).classList.remove('perma');
        document.getElementById(name + (id + 2).toString()).classList.remove('perma');
        document.getElementById(name + (id + 3).toString()).classList.remove('perma');
        document.getElementById(name + (id + 4).toString()).classList.remove('perma');
    }
    if (id == 2) {
        document.getElementById(name + (id - 1).toString()).classList.add('perma');
        document.getElementById(name + id.toString()).classList.add('perma');
        document.getElementById(name + (id + 1).toString()).classList.remove('perma');
        document.getElementById(name + (id + 2).toString()).classList.remove('perma');
        document.getElementById(name + (id + 3).toString()).classList.remove('perma');
    }
    if (id == 3) {
        document.getElementById(name + (id - 2).toString()).classList.add('perma');
        document.getElementById(name + (id - 1).toString()).classList.add('perma');
        document.getElementById(name + id.toString()).classList.add('perma');
        document.getElementById(name + (id + 1).toString()).classList.remove('perma');
        document.getElementById(name + (id + 2).toString()).classList.remove('perma');
    }
    if (id == 4) {
        document.getElementById(name + (id - 3).toString()).classList.add('perma');
        document.getElementById(name + (id - 2).toString()).classList.add('perma');
        document.getElementById(name + (id - 1).toString()).classList.add('perma');
        document.getElementById(name + id.toString()).classList.add('perma');
        document.getElementById(name + (id + 1).toString()).classList.remove('perma');
    }
    if (id == 5) {
        document.getElementById(name + (id - 4).toString()).classList.add('perma');
        document.getElementById(name + (id - 3).toString()).classList.add('perma');
        document.getElementById(name + (id - 2).toString()).classList.add('perma');
        document.getElementById(name + (id - 1).toString()).classList.add('perma');
        document.getElementById(name + id.toString()).classList.add('perma');
    }
}