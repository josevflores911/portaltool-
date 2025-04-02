$(document).ready(function() {
    var card_body = $(".card-body");
    let form_cadastro = card_body.find("form#formCadastro");
    let sel_estado = form_cadastro.find("#estadoSelect");
    let sel_municipios = form_cadastro.find("#municipioSelect");
    
    let cd_estado = sel_estado.val();
    if (cd_estado !== undefined) {
        if (cd_estado.length > 0) {
            carrega_municipios(cd_estado);
        }
    }
    function carrega_municipios(cd_estado) {
        var card_body = $(document.querySelector('.card-body'));
        let form_cadastro = card_body.find("form#formCadastro");
        $.ajax({
            url: "modules/ler_cadmunicipios.php",
            method: "GET",
            data: JSON.stringify({cd_estado: cd_estado}),
            dataType: "json",
            error: function(xhr, status, error) {
               console.log(xhr.responseText, status, error);
            },
            success: function(response) {
                sel_municipios.empty();
                sel_municipios.append(`<option value="0">Escolha</option>`);
                response.forEach((vlist,ix) => {
                    sel_municipios.append(`<option value="${vlist.id_muni}">${vlist.nm_muni}</option>`);
                });
            }
        });
    }

   
    
});

