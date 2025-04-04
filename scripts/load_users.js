$(document).ready(function(evt) {
    var id_user = $(".id_user").val();
    var tp_user = $(".tp_user").val();
    let sel_users = $("select#sel_users");

    $.ajax({
        url: 'modules/ler_usuarios.php',
        type: 'POST',
        data: { id_user: id_user, tp_user: tp_user },
        success: function(data) {
            var resp = JSON.parse(data);
            var error = resp.Error;
            var response = resp.Data;
            if (error == '0') {
                sel_users.empty();
                response.forEach((elem,ix) => {
                    if (ix == 0) {
                        sel_users.append(`<option data-tp_user='' value="0" selected>Todos</option>`);
                    }
                    sel_users.append(`<option data-tp_user='${elem.cd_currposition}' value="${elem.id_user}">${elem.nm_user}</option>`);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});