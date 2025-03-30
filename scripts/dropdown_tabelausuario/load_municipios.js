$(document).ready(function (evt) {
    let sel_estados = $("select#sel_estados");
    let sel_municipios = $("select#sel_municipios");
    sel_estados.on("change", (e) => { 
        let selectedEstado = sel_estados.val();
        $.ajax({
            url: 'modules/dropdown_tabelausuario/ler_municipios.php',
            type: 'POST',
            data: { nm_estado: selectedEstado },
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
                    sel_municipios.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});