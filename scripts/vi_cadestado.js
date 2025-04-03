
$(document).ready(function() {
    var card_body = $(".card-body");
    let form_cadastro = card_body.find("form#formCadastro");
    let sel_estado = form_cadastro.find("#estadoSelect");
    let bt_salvar = $(document.querySelector("button[type='submit']"));

    $.ajax({
        url: "modules/ler_estados.php",
        method: "GET",
        dataType: "json",
        error: function(xhr, status, error) {
            alert("Falha ao carregar estados: " + error);
        },
        success: function(response) {
            sel_estado.empty();
            sel_estado.append(`<option value="">Escolha</option>`);
            response.forEach((vlist,ix) => {
                sel_estado.append(`<option value="${vlist.cd_estado}">${vlist.nm_estado}</option>`);
            });
            
        }
    });

    sel_estado.on('focus', function(evt) {
        form_cadastro.children().find("#lb-municipio").html("")
    });

    sel_estado.on('change, click', (evt) => {
        let cd_uf = $(evt.target).val();
        var card_body = $(document.querySelector('.card-body'));
        let form_cadastro = card_body.find("form#formCadastro");
        let sel_municipios = form_cadastro.find("#municipioSelect");
        if (cd_uf.length > 0) {
            var payload = {
                cd_estado: cd_uf,
            }
            $.ajax({
                url: "modules/ler_cadmunicipios.php",
                method: "GET",
                data: payload,
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
});
