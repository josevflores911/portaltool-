$(document).ready(function (evt) {
    let sel_municipios = $("select#sel_municipios");
    let sel_agencias = $("select#sel_agencias");
    sel_municipios.on("change", (e) => { 
        let selectedMunicipioId = sel_municipios.val();
        $.ajax({
            url: 'modules/dropdown_tabelausuario/ler_agencias.php',
            type: 'POST',
            data: { id_muni: selectedMunicipioId },
            success: function (data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                if (error == '0') {
                    sel_agencias.empty();
                    response.forEach(function(item,ix) {
                        if (ix == 0) {
                            sel_agencias.append("<option value='0' selected>Todos</option>");
                        }
                        sel_agencias.append("<option value='" + item.id_agencia + "'>" + item.nm_agencia + "</option>");
                    });
                    sel_agencias.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});