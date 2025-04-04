$(document).ready(function() {
    let id_agencia = $('#id_agencia');
    let id_user = $('#id_user');
    let modal_anexo = $(".modal.fade#modal_anexo");
    console.log(modal_anexo);
    let modal_bodyanexo = modal_anexo.find(".modal-body");
        // captura o número da nota
    
    bt_confirm = modal_anexo.children().find("button#confirm");
    bt_cancel = modal_anexo.children().find("button#cancel");
    bt_fechar = modal_anexo.children().find("button.button-close");

    bt_cancel.on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        bt_fechar.trigger("click");
    });
  
    bt_confirm.on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        parms = {
            id_agencia: id_agencia,
            id_user: id_user
        }
    });

    bt_fechar.on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        modal_anexo.modal('hide');
    });
});