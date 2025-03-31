$(document).ready(() => {


    // let bt_salvar = document.querySelector('#btsalvar');    
    // let bt_atualizar = document.querySelector('#btatualizar');  

    let bt_salvar = $('#btsalvar');    
    let bt_atualizar = $('#btatualizar');
    
    let sel_agencias = $("select#sel_agencias");

    sel_agencias.on("change", (e) => { 
        bt_salvar.prop('disabled', false);
        bt_atualizar.prop('disabled', false);
    })
    
    
    $(bt_salvar).on("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        let nome = $("#nome").val();
        let codigo = $("#codigo").val();
        let senha = $("#senha").val();
        let sel_tiposusuario = $("#sel_tiposusuario").val();
        let sel_estados = $("#sel_estados").val();
        let sel_municipios = $("#sel_municipios").val();
        let sel_agencias = $("#sel_agencias").val();

       
        $.ajax({
            url: 'modules/gravar_tabelausuario.php',
            type: 'POST',
            data: { nome: nome, codigo: codigo, senha: senha, sel_tiposusuario: sel_tiposusuario, sel_agencias: sel_agencias },
            success: function(data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                if (error == '0' && response.length > 0) {
                    var item = response[0]; 
                    
                    console.log("a", item);
                    
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

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
                    console.log("a", item);
                    
                    nomeInput.val(item.nome_usuario);
                    codigoInput.val(item.codigo_usuario);
                    senhaInput.val(item.senha);
                    
                    
    
                    sel_tiposusuario.append("<option value='" + item.tipo_usuario + "'>" + item.tipo_usuario + "</option>");
                    sel_estados.append("<option value='" + item.estado_usuario + "'>" + item.estado_usuario + "</option>");
                    sel_municipios.append("<option value='" + item.indice + "'>" + item.municipio_usuario + "</option>");
                    sel_agencias.append("<option value='" + item.indice + "'>" + item.agencia_usuario + "</option>");
                    
                    // // // sel_tiposusuario.html('')
                    // // // sel_tiposusuario.append(item.tipo_usuario);
                    // // // sel_estados.html('');
                    // // // sel_estados.append(item.estado_usuario);
                    // // sel_municipios.html('');
                    // // sel_municipios.append( item.municipio_usuario );
                    // // sel_agencias.html('');
                    // // sel_agencias.append( item.agencia_usuario );
    
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
