$(document).ready(function(evt) {
    var id_user = $(".id_user").val();
    var tp_user = $(".tp_user").val();
    let sel_statusmuni = $("select#sel_statusmuni");
    $.ajax({
        url: 'modules/ler_status.php',
        type: 'POST',
        success: function(data) {
            var resp = JSON.parse(data);
            var error = resp.Error;
            var response = resp.Data;
            if (error == '0') {
                sel_statusmuni.empty();
                response.forEach((elem,ix) => {
                    if (ix == 0) {
                        sel_statusmuni.append(`<option value="0" selected>Todos</option>`);
                    }
                    sel_statusmuni.append(`<option value="${elem.cd_status}">${elem.te_descricao}</option>`);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});