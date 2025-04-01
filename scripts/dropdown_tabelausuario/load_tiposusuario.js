$(document).ready(function(evt) {
    carregarTiposusuario();

    function carregarTiposusuario() {
        let sel_tiposusuario = $("select#sel_tiposusuario");
        $.ajax({
            url: 'modules/dropdown_tabelausuario/ler_tiposusuario.php',
            type: 'POST',
            success: function (data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                
                if (error == '0') {
                    sel_tiposusuario.empty();
                    response.forEach(function (item, ix) {
                      
                        if (ix == 0) {
                            sel_tiposusuario.append("<option value='0' selected>Todos</option>");
                        }
                        sel_tiposusuario.append("<option value='" + item.indice + "'>" + item.cd_currposition + "</option>");
                    });
                   
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
   
});