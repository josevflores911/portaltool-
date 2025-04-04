$(document).ready(function() {
    var id_nota = parseInt(window.localStorage.getItem('id_nota'));

    var id_user = parseInt(window.localStorage.getItem('id_user'));
    var module = window.localStorage.getItem("module");
    if (module == "ntservicos") {
        var nm_module = "modules/del_notas.php";
    } else if (module == "ntconsumo") {
        var nm_module = "modules/del_notasconsumo.php";
    } else if (module == "usuarios") {
        var header = $(".modal#show_msgdelete").find(".modal-header");
        var h4 = header.children().find("h4");
        h4.html("");
        h4.html("Exclusão de usuário");
        var label = $(".modal#show_msgdelete").children().find("label[for='input_nfse']");
        label.html("");
        label.html("Usuário:")
        var nm_module = "modules/del_usuario.php";
    } else if (module == "ntsefaz") {
        var nm_module = "modules/del_ntsefaz.php";
    }
   
    let modal_delete = $(".modal.fade#show_msgdelete");
    let modal_bodydelete = modal_delete.find(".modal-body");
    let input_nota= modal_bodydelete.children().find("input#input_nunota");

    // captura o número da nota
    
    let generic = modal_delete.parents("div.generic")

    let parent_tbody=null;
    if (module === "ntservicos") {
        parent_tbody =generic.parents('div.principal').find("table > tbody#tbody_service");
    } else if (module === "ntconsumo") {
        parent_tbody =generic.parents('div.principal').find("table > tbody#tbody_consumo");
    } else if (module == "usuarios") {
        parent_tbody =generic.parents('div.principal').find("table > tbody#tbody_usuarios");
    }
    let vtr = parent_tbody.children("tr");
    
    if (module !== 'usuarios') {
        var curr_tr = vtr.filter((ix, elem) => {
            var obj = $(elem);
            var first_td = obj.children('td').eq(0);
            var id = first_td.data("idnota") || first_td.prop("data-idnota");
            return (id === id_nota);
        });
    } else {
        let curr_tr =vtr.filter((ix,elem) => {
            var tds = $(elem).children("td");
            var id = tds.eq(0).data("id");
            return id == id_nota;
        });
    }
    var nu_nota = curr_tr.children("td").eq(2).text();
    if (nu_nota.length == 0) {
        var id = curr_tr.children('td:eq(0)').data('idnota') || curr_tr.children('td:eq(0)').prop('data-idnota');
        nu_nota = "regitro:"+id;
    }

    input_nota.prop("disabled", false);
    input_nota.val(nu_nota);
    input_nota.prop("disabled", true);

    bt_confirm = $(".modal-body").children().find("button#confirm");
    bt_cancel = $(".modal-body").children().find("button#cancel");
    bt_fechar = $(".modal.fade").children().find("button.modal-close");

    bt_cancel.on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        bt_fechar.trigger("click");
    });

    bt_confirm.on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        parms = {
            id_nota: id_nota,
            id_user: id_user
        }
    
        $.get(nm_module, parms, (data) => {
   
            var resp = JSON.parse(data);
            if (resp.Error == '0') {
                curr_tr.remove();
                bt_fechar.trigger("click");
            }
        });
       
    });
});