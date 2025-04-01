$(document).ready(function (evt) {
    let sel_estados = $("select#sel_estados");
    
    //div inicial 
    sel_estados.on("change", (e) => { 
        let sel_municipios = $("select#sel_municipios");   
        let selectedEstado = sel_estados.val();
        // console.log($('#item').attr('name'));
        carregarMunicipios(sel_municipios,selectedEstado);
    });

    //divs dinamicos
    $(document).on("change", ".form-select", function () {
        //need to be state
        var dinamic_selEstado = $(this);
        
        // var id = dinamic_selEstado.attr('id');
        // console.log("El select con id " + id + " fue modificado.");
        // let itemid = id.substring(id.lastIndexOf("_") + 1);
        // console.log(`select#sel_municipios_${itemid}`);
        // var name = dinamic_selEstado.attr('name');
        // console.log("El select con name " + name + " fue modificado.");
        // var selectedValue = dinamic_selEstado.val();
        // console.log("Valor seleccionado: " + selectedValue);
       
        setTimeout(function () {
            var index_item = $(".form-login").children("#item").length - 1;
            let dinamic_selMunicipios = $(`select#sel_municipios_${index_item}`);
            
            let val_selEstado = dinamic_selEstado.val();
            carregarMunicipios(dinamic_selMunicipios, val_selEstado);
        },200)
    });

    function carregarMunicipios(sel_municipiostemplate, selectedEstadonome) {
        $.ajax({
            url: 'modules/dropdown_tabelausuario/ler_municipios.php',
            type: 'POST',
            data: { nm_estado: selectedEstadonome },
            success: function (data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
                if (error == '0') {
                    sel_municipiostemplate.empty();
                    // sel_municipiostemplate.html('');
                    response.forEach(function(item,ix) {
                        if (ix == 0) {
                            //sel_municipios.append("<option value='0' selected>Todos</option>");
                        }
                        sel_municipiostemplate.append("<option value='" + item.id_muni + "'>" + item.nm_muni + "</option>");
                        
                    });
                    sel_municipiostemplate.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
});