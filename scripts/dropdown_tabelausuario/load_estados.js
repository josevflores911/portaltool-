$(document).ready(function (evt) {
    let sel_estados = $("select#sel_estados");
    carregarEstados(sel_estados)

    $(document).on("click", "#btn_addcolumn", function (e) { 
        var items = $(".form-login").children("#item"); 
        setTimeout(function () {
            var index_item = $(".form-login").children("#item").length-1;
            let dinamic_selEstados = $(`select#sel_estados_${index_item}`);
            carregarEstados(dinamic_selEstados);
        }, 200);
    });

    function carregarEstados(sel_estados_o) {
        $.ajax({
            url: 'modules/dropdown_tabelausuario/ler_estados.php',
            type: 'POST',
            success: function (data) {
                
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                
                if (error == '0') {
                    sel_estados_o.empty();
                    response.forEach(function(item,ix) {
                      
                        if (ix == 0) {
                            // sel_estados_o.append("<option value='0' selected>Todos</option>");
                        }
                        sel_estados_o.append("<option value='" + item.nm_estado + "'>" + item.nm_estado + "</option>");
                    });
                   
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    
});