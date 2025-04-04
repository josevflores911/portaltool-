$(document).ready(() => {

    var id_nota = window.localStorage.getItem('id_nota');
    var id_user = window.localStorage.getItem('id_user');
    id_nota = parseInt(id_nota);
    id_user = parseInt(id_user);
    let box_notificaçoes = $(".modal.fade").children().find("#box-notificaçoes");
    let email_panel = box_notificaçoes.children().find("#envios");
    let bt_annex = email_panel.children().find("#bt-annex");
    let bt_sendemail = email_panel.children().find("button.bt-submit");
    let bt_cancelemail = email_panel.children().find("button.bt-clear");

    bt_annex.on("click",(evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        var obj =$(evt.target);
        let input_annex = obj.parents().find("#upload_anexos");
        let input_filesannex = obj.parents().find("#file_anexo");
        select_annex(input_annex);
        input_annex.on("change", (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            var files = $(evt.target).get(0).files;
            if (files.length > 0) {
                input_filesannex.prop("disabled", false);
                var nlength = files.length;
                let tbody_files = $(".tb-emailanexo").find("tbody");
                let tr_files = $(".tb-emailanexo").find("tbody>tr");
                var nlen = tr_files.length;
                if (nlength > nlen) {
                    var dif = nlength - nlen;
                    while (dif > 0) {
                        var last_tr = tbody_files.find("tr:last");
                        var new_tr = last_tr.clone(true);
                        tbody_files.append(new_tr);
                        dif -=1;
                    }
                }
                tr_files = $(".tb-emailanexo").find("tbody>tr");
                var ntotal = 0;
                for (var i = 0; i < files.length; i++) {
                    var name = files[i].name;
                    var size = files[i].size;
                    ntotal += parseInt(size);
                    var tr_pos = tr_files.eq(i);
                    var inputs = tr_pos.children().find("input");
                    inputs.eq(0).prop("checked", true);
                    inputs.eq(1).val(name);
                    inputs.eq(2).val(size);
                    list_anexos.push(files[i]);
                }

                let tfoot_files = $(".tb-emailanexo").find("tfoot");
                let input_tsize = tfoot_files.find("input#total_bytes");
                input_tsize.val(ntotal);
            }
        });
    });


    function select_annex(obj) {
        obj.unbind("click");
        obj.unbind("change");
        obj.trigger("click");
    }

    bt_sendemail.on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        var obj = $(evt.target);
        let inputs = $(".tb-envio").children().find("input");
        
        let lbl_email = email_panel.children().find("#msg_envio");
        let textarea = email_panel.children().find("textarea");
        var benviar=true;

        inputs.each ((ix, elem) => {
            var id =$(elem).attr("id");
            if (id !== "email_copy" && 
                id !== "chkanexo" && 
                id !== "file" && 
                id !== "size") {
                    if ($(elem).val().length == 0) {
                        var label = email_panel.children().find("label[for='"+id+"']");
                        var field = label.text();
                        if (field.length > 0) {
                            var mensagem = `<b><i style='color:red'>Campo ${field} é obrigatório</i></b>`;
                            lbl_email.html(mensagem);
                            $(elem).focus();
                            benviar=false;
                            return false;
                        }
                    }
                }
        });


        if (benviar) {
            if (textarea.val().length == 0) {
                var mensagem = `<b><i style='color:red'>Campo Mensagem é obrigatório</i></b>`;
                lbl_email.html(mensagem);
                benviar=false;
                textarea.focus();
            } 
        } 
        let input_annexs = obj.parents().find("#upload_anexos");
        let chk_anexos = obj.parents().find("input#chkanexo");
        var list_anexos = input_annexs.get(0).files;  
        if (benviar) {
            // carregar anexos para o diretorio nt_servicos_anexos, se houver
            var file_anexos = [];
            obj.css("cursor", "wait");
            if (list_anexos.length > 0) {
                lbl_email.html(`<b><i style='color:red'>Carregando anexos</i></b>`);
                for (var i = 0; i < list_anexos.length; i++) {
                     if (chk_anexos.eq(i).is(':checked') == false) continue;
                    var objfile = list_anexos[i];
                    var cfile = objfile.name;
                    var dt_aux = getuDtHoje();
                    var cfiledest = `../uploads/${id_nota.toString()}_${dt_aux}_${cfile}`;
                    file_anexos.push(cfiledest.trim());
                    let request = new XMLHttpRequest();
                    request.upload.addEventListener("progress", function (ev) {
                        if (ev.lengthComputable) {
                            var perc = Math.round(ev.loaded / ev.total, 2) * 100;
                            var mensagem = `<b><i style='color:red'>Upload (${cfile}- ${perc}</i></b>`;
                            lbl_email.html("");
                            lbl_email.html(mensagem);
                        }
                    });

                    request.addEventListener('load', function (e) {
                        let resp = e.currentTarget.responseText;
                        resp = JSON.parse(resp);
                        if (resp.Error == '0') {
                            lbl_email.html(`<b><i style='color:red'>Upload completo</i></b>`);
                        // enviar mensagem
                        } else {
                            obj.css("cursor","pointer");
                            return false;
                        }
                    });
                        // arquivo gerado sera {id_nota}_{datahora}_{nome do arquivo}
                    setTimeout(() => {
                        let formdata = new FormData();
                        var n_auxi = "0";
                        formdata.append("file", objfile);
                        formdata.append('file_out',cfiledest);
                        formdata.append("id_nota", id_nota);
                        formdata.append("n_auxi", n_auxi);
                        request.open("post", 'modules/upload_mailannex.php');
                        request.send(formdata);
                    }, 5000);
                }
            } 

            list_anexos = JSON.stringify(file_anexos);
            var parms = { 
                id_user : id_user,
                id_nota : id_nota,
                to: inputs.eq(0).val(),
                name: inputs.eq(1).val(),
                cc: inputs.eq(2).val(),
                subject: inputs.eq(3).val(),
                body: textarea.val(),
                anexos: list_anexos
            };
        
            $.post("modules/send_email.php",parms, (data) => {
                var resp = JSON.parse(data);
                if (resp.Error == '0') {
                    var err_mail = resp.Error_email;
                    if (err_mail.length == 0) {
                        var msg = resp.Message;
                        lbl_email.html(`<b><i style='color:red'>${msg}</i></b>`); 
                    } else {
                        lbl_email.html(`<b><i style='color:red'>${err_mail}</i></b>`); 
                    }

                } else {
                    lbl_email.html(`<b><i style='color:red'>Ocorreu um erro (${resp.Error}) - ${resp.Message}</i></b>`); 
                }
                obj.css("cursor", "pointer");
            }); 
        
        }
    });
});
