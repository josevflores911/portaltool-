
$(document).ready(function() {
    let div_program = $(".generic");
    $.ajax({
        url: 'modules/ler_tabelausuarios.php',
        type: 'POST',
        success: function(data) {
            // console.log(data);
            var resp = JSON.parse(data);
            var response = resp.Data;
            var tbodyContent = '';
            response.forEach(function(item, index) {
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
                                        <a type="button" class="btn btn-success btn-sm btn-editar" data-id="${item.indice}">Editar</a>
                                    </div>
                                    <div>
                                        <form action="services/controller.php" method="POST" class="d-inline">
                                        <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_usuario" value="${item.indice}" class="btn btn-danger btn-sm">
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
            $('tbody').html(tbodyContent);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
 
 
 
    $(document).on("click", ".btn-editar", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var userId = $(this).data('id'); 
        $.get('views/vi_editarusuario.html', { id: userId }, function (data) { 
                        
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
            modal.append('<h1 style="color:red;">'+userId+'</h1>');
            modal.modal('show');
    
        });
       
    });
 
  
 
    // $(document).on("click", ".btn-editar", function(e) {
    //     e.preventDefault();
    //     e.stopPropagation();
    //     var userId = $(this).data('id'); 
    
    //     $.ajax({
    //         url: 'modules/ler_userbyid.php',
    //         type: 'POST',
    //         data: { id_user: userId },
    //         success: function(data) {
    //             var resp = JSON.parse(data);
    //             var error = resp.Error;
    //             var response = resp.Data;
    //             if (error == '0' && response.length > 0) {
    //                 var item = response[0]; 
    
    //                 $.get('views/vi_criarusuario.html', { id: userId }, function(data) {
    //                     // Cargar el contenido en el modal
    //                     $('#nome').val(item.nome_usuario);
    //                     $('#codigo').val(item.codigo_usuario);
    //                     // $('#senha').val(item.senha_usuario); 
    //                     // $('#tipo_usuario').val(item.tipo_usuario); 
    //                     // $('#estado').val(item.estado_usuario); 
    //                     // $('#municipio').val(item.municipio_usuario); 
    //                     // $('#agencia').val(item.agencia_usuario);
    
                        
    //                     div_program.html('');
    //                     div_program.html(data);
    //                     let modal = $(".modal");
    //                     modal.modal('show');
    //                 });
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error:', error);
    //         }
    //     });
    // });
    
  
 });