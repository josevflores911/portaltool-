$(document).ready(function() {
    $.ajax({
        url: 'modules/ler_tabelausuarios.php',
        type: 'POST',
        success: function(data) {
            console.log(data);
            var resp = JSON.parse(data);
            var response = resp.Data;

            var tbodyContent = '';

            // Recorre los datos y genera las filas de la tabla
            response.forEach(function(item, index) {
                var row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nome_usuario}</td>
                        <td>${item.codigo_usuario}</td>
                        <td>${item.status_ativo}</td>
                        <td>${item.tipo_usuario}</td>
                        <td>${item.estado_usuario}</td>
                        <td>${item.municipio_usuario}</td>
                        <td>${item.agencia_usuario}</td>
                        
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