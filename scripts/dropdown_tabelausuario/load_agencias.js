$(document).ready(function (evt) {
    let sel_municipios = $("select#sel_municipios");
    let sel_agencias = $("select#sel_agencias");
    
    
    sel_municipios.on("change", (e) => { 
        let selectedMunicipioId = sel_municipios.val();
        carregarMunicipios(sel_agencias,selectedMunicipioId);
    });

    $(document).on("change", ".form-select", function () {
        //need to be state
        var dinamic_selMunicipio = $(this);
       
        setTimeout(function () {
            var index_item = $(".form-login").children("#item").length - 1;
                let dinamic_selAgencias= $("select#sel_agencias");
            if (index_item > 1) {
                dinamic_selAgencias = $(`select#sel_agencias_${index_item}`);
            } else {
                //
            }
            
            let val_selMunicipio = dinamic_selMunicipio.val();
            carregarAgencias(dinamic_selAgencias, val_selMunicipio);
        },200)
    });



    function carregarAgencias(sel_agenciatemplate, selected_municipioid) { 
        $.ajax({
            url: 'modules/dropdown_tabelausuario/ler_agencias.php',
            type: 'POST',
            data: { id_muni: selected_municipioid },
            success: function (data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                if (error == '0') {
                    sel_agenciatemplate.empty();
                    response.forEach(function(item,ix) {
                        if (ix == 0) {
                            sel_agenciatemplate.append("<option value='0' selected>Todos</option>");
                        }
                        sel_agenciatemplate.append("<option value='" + item.id_agencia + "'>" + item.nm_agencia + "</option>");
                    });
                    sel_agenciatemplate.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
});