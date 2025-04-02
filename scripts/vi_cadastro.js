$(document).ready(function (){
    var card_body = $(document.querySelector('#card-body'));
    let form_cadastro = card_body.find("form#formCadastro");
    let sel_estado = form_cadastro.find("#estadoSelect");
    let sel_municipios = form_cadastro.find("#municipioSelect");
    let table_agencia = $(".table-agencias > table");
    let tbody = table_agencia.find("tbody");
      
   
    $('.submit-button').on('click', () => {
        $.ajax({
            type: "POST",
            url: "/api/tela_cadastro_registro",
            data: $("#form-cadastro").serialize(),
            success: function (response){
                alert(response.message);
            },
            error: function (response) {
                console.log("Error" + response);
                console.log(response);
            }
        })
    });
});

function updateForm(evt) {
    var card_body = $(document.querySelector('#card-body'));
    let form_cadastro =$(document.querySelector("form#formCadastro"));
    
    let input_nmsistema = form_cadastro.children().find("#nm_sistema");
    let input_linkp = form_cadastro.children().find("#te_link");
    let input_teusuariop = form_cadastro.children().find("#nm_usuario");
    let input_te_senhap = form_cadastro.children().find("#te_senha");
    let input_linkt = form_cadastro.children().find("#te_linkt");
    let input_teusuariot = form_cadastro.children().find("#nm_usuariot");
    let input_te_senhat = form_cadastro.children().find("#te_senhat");

    let input_contato = form_cadastro.children().find("nm_contato");
    let input_contato_email = form_cadastro.children().find("#te_email");
    let input_contato_telefone = form_cadastro.children().find("#te_telefone");

    let input_contato_sup = form_cadastro.children().find("nm_contato_sup");
    let input_contato_email_sup = form_cadastro.children().find("#te_email_sup");
    let input_contato_telefone_sup = form_cadastro.children().find("#te_telefone_sup");

    let input_elaborador = form_cadastro.children().find("#nm_elaborador");
    let input_validador = form_cadastro.children().find("#nm_aprovador");

    var row = $(evt.target).closest("tr");
    var obj = $(evt.target);
    var id_agenciaxmunicipio = row.data("idagenciaxmunicipio") || row.prop("data-idagenciaxmunicipio");
    var id_agencia = obj.val();
        
    var nm_sistema = row.children('td').eq(6).text();
    var cd_estado = row.data("cdestado") || row.prop('data-cdestado');
    var nm_municipio = row.data("nmmuni") || row.prop('data-nmmuni');
    
    var id_labelmuni = $(document.querySelector(".lb-municipio"));
    id_labelmuni.html(`<i>${nm_municipio}/${cd_estado}</i>`);
    
    var nm_linkp = row.children('td:eq(7)').text();
    var te_usuariop = row.children('td:eq(8)').text();
    var te_senhap = row.children('td:eq(9)').text();
    var nm_linkt = row.children('td:eq(10)').text();
    var te_usuariot = row.children('td:eq(11)').text();
    var te_senhat = row.children('td:eq(12)').text();

    var te_contato = row.children('td:eq(13)').text();
    var te_contato_email = row.children('td:eq(14)').text();
    var te_contato_telefone = row.children('td:eq(15)').text();

    var te_contato_sup = row.children('td:eq(16)').text();
    var te_contato_sup_email = row.children('td:eq(17)').text();
    var te_contato_sup_telefone = row.children('td:eq(18)').text();

    input_nmsistema.val(nm_sistema);
    input_linkp.val(nm_linkp);
    input_teusuariop.val(te_usuariop);
    input_te_senhap.val(te_senhap);
    input_linkt.val(nm_linkt);
    input_teusuariot.val(te_usuariot);
    input_te_senhat.val(te_senhat);

    input_contato.val(te_contato);
    input_contato_email.val(te_contato_email);
    input_contato_telefone.val(te_contato_telefone);
    input_contato_sup.val(te_contato_sup);
    input_contato_email_sup.val(te_contato_sup_email);
    input_contato_telefone_sup.val(te_contato_sup_telefone);

    // pega validador e elaborador
    $.ajax({
        url: 'modules/getElaborarValidador.php',
        type: 'GET',
        data: {id_agencia: id_agencia,
            cd_currposition: 'Elaborador',
            cd_responsavel: 'S'
        },
        dataType:'json',
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error:', errorThrown,'Message:', textStatus, 'context:', jqXHR.responseText);
        },

        success: function (response) {
            console.log(response);
            var error = response.Error;
            var data = response.Data[0];
            var message = response.Message;
            if (error == '0'){
                input_elaborador.val(data.nm_user);
            }
        }
    });

    
    // pega validador e elaborador
    $.ajax({
        url: 'modules/getElaborarValidador.php',
        type: 'GET',
        data: {id_agencia: id_agencia,
            cd_currposition: 'Validador',
        },
        dataType:'json',
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error:', errorThrown,'Message:', textStatus, 'context:', jqXHR.responseText);
        },

        success: function (response) {
            console.log(response);
            var error = response.Error;
            var data = response.Data[0];
            var message = response.Message;
            if (error == '0'){
                input_validador.val(data.nm_user);
            }
        }
    });
   
}



