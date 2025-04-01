$(document).ready(() => {
    // let bt_salvar = document.querySelector('#btsalvar');
    // let bt_atualizar = document.querySelector('#btatualizar');  
    
    let nome = $("#nome");
    let codigo = $("#codigo");
    let senha = $("#senha");
    
    nome.val(' ');
    codigo.val(' ');
    senha.val(' ');

    let bt_salvar = $('#btsalvar');    
    let bt_atualizar = $('#btatualizar');
    
    let sel_agencias = $("select#sel_agencias");

    //salvar so com todos os items
    sel_agencias.on("change", (e) => { 
        bt_salvar.prop('disabled', false);
        bt_atualizar.prop('disabled', false);
    })
    
    
    $(bt_salvar).on("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        let nome_val = nome.val();
        let codigo_val = codigo.val();
        let senha_val = senha.val();

        if (nome_val && codigo_val && senha_val && nome_val.trim() !== "" && codigo_val.trim() !== "" && senha_val.trim() !== "") {
            
                let sel_tiposusuario = $("#sel_tiposusuario").val();
                let sel_estados = $("#sel_estados").val();
                let sel_municipios = $("#sel_municipios").val();
                let sel_agencias = $("#sel_agencias").val();
    
                $.ajax({
                    url: 'modules/gravar_tabelausuario.php',
                    type: 'POST',
                    data: { nome: nome_val, codigo: codigo_val, senha: senha_val, sel_tiposusuario: sel_tiposusuario, sel_agencias: sel_agencias },
                    success: function (data) {
                        var resp = JSON.parse(data);
                        var error = resp.Error;
                        var response = resp.Data;
                        if (error == '0' && response.length > 0) {
                            var item = response[0];
                        
                            console.log("a", item);
                        
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });

                var modal = new bootstrap.Modal($('#criar_usuario')[0]);
                modal.hide();
           
        } else {
            alert("Por favor, complete todos os campos.");
        }

    });


    $(document).on("click", "#btn_addcolumn", function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var index_item = $(".form-login").children("#item").length;
        console.log("from form buttom",index_item)

        $(e.target).val(index_item)
        
        var nuevaDiv = $(`
                        <div class="row mb-3" id="item">
                           <div class="col-md-4">
                              <label class="text-dark">Estado</label>
                              <select id="sel_estados_${index_item}" name="estado" class="form-select" required>
                                 <option value="" disabled selected>Selecione um estado</option>
                              </select>
                           </div>
                           <div class="col-md-4" id="sel_municipios">
                              <label for="municipio" class="text-dark">Municipio:</label>
                              <select id="sel_municipios_${index_item}" name="municipio" class="form-select"  required disabled>
                                 <option value="">Seleccionar Municipio</option>
                              </select>
                           </div>
                           <div class="col-md-4" id="sel_agencias">
                              <label for="agencia" class="text-dark">Agência:</label>
                              <select id="sel_agencias_${index_item}" name="agencia" class="form-select"  required disabled>
                                 <option value="">Seleccionar Agência</option>
                              </select>
                           </div>                         
                        </div>
                        `);

      
        var contenedor = $(".form-login");
        var items = contenedor.children("#item"); 
        
        if (items.length > 1) {
            items.eq(items.length - 2).after(nuevaDiv);
        } else {
            contenedor.append(nuevaDiv);
        }
    });
 

    //TODO 
    $(bt_atualizar).on("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $.ajax({
            url: 'modules/ler_userbyid.php',
            type: 'POST',
            data: { id_user: selectedId },
            success: function(data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                if (error == '0' && response.length > 0) {
                    var item = response[0]; 
                    
                    nomeInput.val(item.nome_usuario);
                    codigoInput.val(item.codigo_usuario);
                    senhaInput.val(item.senha);
    
                    sel_tiposusuario.append("<option value='" + item.tipo_usuario + "'>" + item.tipo_usuario + "</option>");
                    sel_estados.append("<option value='" + item.estado_usuario + "'>" + item.estado_usuario + "</option>");
                    sel_municipios.append("<option value='" + item.indice + "'>" + item.municipio_usuario + "</option>");
                    sel_agencias.append("<option value='" + item.indice + "'>" + item.agencia_usuario + "</option>");
                    
    
                    // sel_municipios.prop('disabled', false);
                    // sel_agencias.prop('disabled', false);
                    
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

});
