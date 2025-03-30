$(document).ready(function(evt) {
    var id_user = $(".id_user").val();
    var tp_user = $(".tp_user").val();
    let sel_municipios = $("select#sel_municipios");
    $.ajax({
        url: 'modules/ler_municipios.php',
        type: 'POST',
        data: { id_user: id_user, tp_user: tp_user },
        success: function (data) {
            var resp = JSON.parse(data);
            var error = resp.Error;
            var response = resp.Data;
            if (error == '0') {
                sel_municipios.empty();
                response.forEach(function(item,ix) {
                    if (ix == 0) {
                        sel_municipios.append("<option value='0' selected>Todos</option>");
                    }
                    sel_municipios.append("<option value='" + item.id_muni + "'>" + item.nm_muni + "</option>");
                });
               
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});