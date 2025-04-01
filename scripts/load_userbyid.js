$(document).ready(function () {
    let div_item = $("#hidden_id");
    let nomeInput = $("input#nome");  
    let codigoInput = $("input#codigo");  
    let senhaInput = $("input#senha");  
    let sel_tiposusuario = $("select#sel_tiposusuario");  
    let sel_estados = $("select#sel_estados");  
    let sel_municipios = $("select#sel_municipios");  
    let sel_agencias = $("select#sel_agencias");  
    
    let selectedId = div_item.val();
    $.ajax({
        url: 'modules/ler_userbyid.php',
        type: 'POST',
        data: { id_user: selectedId },
        success: function (data) {
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