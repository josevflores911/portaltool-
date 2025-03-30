// $(document).ready(function(evt) {
    
//     // let sel_statusmuni = $("select#sel_statusmuni");
//     console.log("herea")
            
//     $.ajax({
//         url: 'modules/ler_tabelausuarios.php',
//         type: 'POST',
//         success: function (data) {
//             console.log("here")
//             console.log(data)
//             // var resp = JSON.parse(data);
//             // var error = resp.Error;
//             // var response = resp.Data;
//             // if (error == '0') {
//             //     sel_statusmuni.empty();
//             //     response.forEach((elem,ix) => {
//             //         if (ix == 0) {
//             //             sel_statusmuni.append(`<option value="0" selected>Todos</option>`);
//             //         }
//             //         sel_statusmuni.append(`<option value="${elem.cd_status}">${elem.te_descricao}</option>`);
//             //     });
//             // }
//         },
//         error: function(xhr, status, error) {
//             console.error('Error:', error);
//         }
//     });
// });

$(document).ready(function() {
  
    $.ajax({
        url: 'modules/ler_tabelausuarios.php',
        type: 'POST',
        success: function(data) {

            var resp = JSON.parse(data);
            var response = resp.Data;

            var tbodyContent = '';

            response.forEach(function(item, index) {
                var row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nome_usuario}</td>
                        <td>${item.codigo_usuario}</td>
                        <td>${item.tipo_usuario}</td>
                        <td>${item.estado_usuario}</td>
                        <td>${item.municipio_usuario}</td>
                        <td>${item.agencia_usuario}</td>
                        <td>01/01/2023</td> <!-- Suponiendo una fecha de ejemplo -->
                        <td>01/01/2024</td> <!-- Suponiendo una fecha de ejemplo -->
                        <td>
                            <div style="display:flex; flex-direction:row; justify-content:space-around">
                                <div>
                                    <a href="views/edit-view.php?id=${item.id_usuario}" class="btn btn-success btn-sm">Editar</a>
                                </div>
                                <div>
                                    <form action="services/controller.php" method="POST" class="d-inline">
                                        <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_usuario" value="${item.id_usuario}" class="btn btn-danger btn-sm">
                                            <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                tbodyContent += row;
            });

            // Insertar el contenido generado en el tbody de la tabla
            $('tbody').html(tbodyContent);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});
