$(document).ready(function(evt) {
    var id_user = $(".id_user").val();
    var tp_user = $(".tp_user").val();
    let sel_compet = $("select#sel_competencias");
    $.ajax({
        url: 'modules/ler_competencias.php',
        type: 'POST',
        data: { id_user: id_user, tp_user: tp_user },
        success: function(data) {
            var resp = JSON.parse(data);
            var error = resp.Error;
            var response = resp.Data;
            if (error == '0') {
                sel_compet.empty();
                response.forEach((elem,ix) => {
                    if (ix == 0) {
                        sel_compet.append(`<option selected value="${elem.dt_compet}">${elem.dt_compet}</option>`);
                        sel_compet.append("<optgroup label='Outras competências'>");
                    } else {
                        sel_compet.find('optgroup').append(`<option value="${elem.dt_compet}">${elem.dt_compet}</option>`);
                    }
                });
                sel_compet.append("</optgroup>");
                
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});