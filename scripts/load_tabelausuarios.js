

$(document).ready(function() {
    let div_program = $(".generic");
    load_userdata();

    function load_userdata(){
        $.ajax({
            url: 'modules/ler_tabelausuarios.php',
            type: 'POST',
            success: function(data) {
                var resp = JSON.parse(data);
                var response = resp.Data;
                var tbodyContent = '';
                response.forEach(function (item, index) {
                    //error al editar por indice modificar clase para usar user id
                    var row = `
                                <tr>
                                <td>${item.indice}</td>
                                <td>${item.nome_usuario}</td>
                                <td>${item.codigo_usuario}</td>
                                <td>${item.status_ativo=="S" ?"Ativo": "Inativo"}</td>
                                <td>${item.tipo_usuario}</td>
                                <td>${item.estado_usuario}</td>
                                <td>${item.municipio_usuario}</td>
                                <td>${item.agencia_usuario}</td>
                                <td>
                                    <div style="display:flex; flex-direction:row; justify-content:space-around">
                                        <div>
                                            <a id="btn-editar" type="button" class="btn btn-success btn-sm" data-id="${item.indice}">Editar</a>
                                        </div>
                                        <div>
                                            <a id="btn_deleteuser" name="delete_usuario" type="button" class="btn btn-danger btn-sm" data-id="${item.id_usuario}">Excluir</a><span class="bi-trash3-fill"></span>&nbsp;
                                        </div>
                                    </div>
                                </td>
                                </tr>
                                `;
                    tbodyContent += row;
                });
                $('#tusers').html('');
                $('#tusers').html(tbodyContent);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
 
    $(document).on("click", "#btn-editar", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var userId = $(this).data('id'); 
        $.post('views/vi_editarusuario.html', { id: userId }, function (data) { 
                        
            var hiddenInput = '<input type="hidden" id="hidden_id" name="hidden_id" value="' + userId + '">';
            // var hiddenInput = $('<input>', {
                //     type: 'hidden',
                //     name: 'user_id',
                //     value: userId
            // });
            
            div_program.html('');
            div_program.html(data);
            let modal = $(".modal");
            modal.append(hiddenInput)
            modal.modal('show');
    
        });
       
    });

    $(document).on("click", "#btn_deleteuser", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var userId = $(this).data('id');
        if (userId) {
            let resposta = confirm("Você deseja continuar?");
            if (resposta) {
                alert(`user  ${userId} sera desativado`);

                $.ajax({
                    url: 'modules/delete_tabelausuario.php',
                    type: 'POST',
                    data: { id_user:userId},
                    success: function (data) {
                        var resp = JSON.parse(data);
                        var error = resp.Error;
                        var response = resp.Data;
                        if (error == '0' && response.length > 0) {
                            var item = response[0];
                        
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


                load_userdata();
            } else {
                
            }
            
        }
       
    });



});
 
