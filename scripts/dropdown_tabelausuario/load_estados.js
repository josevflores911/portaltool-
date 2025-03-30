$(document).ready(function(evt) {

    let sel_estados = $("select#sel_estados");
    $.ajax({
        url: 'modules/dropdown_tabelausuario/ler_estados.php',
        type: 'POST',
        success: function (data) {
            
            var resp = JSON.parse(data);
            var error = resp.Error;
            var response = resp.Data;
            
            if (error == '0') {
                sel_estados.empty();
                response.forEach(function(item,ix) {
                  
                    if (ix == 0) {
                        sel_estados.append("<option value='0' selected>Todos</option>");
                    }
                    sel_estados.append("<option value='" + item.nm_estado + "'>" + item.nm_estado + "</option>");
                });
               
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});