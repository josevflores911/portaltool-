$(document).ready((evt) => {
    let form_dialog = $(".modal.fade#form-aliquota");
    let form_body = form_dialog.children().find("div.modal-body");
    let sel_estado = form_body.children().find("select#sel_estado");
    let sel_municipio = form_body.children().find("select#sel_municipio");
    let sel_grupo = form_body.children().find("select#sel_grupo");
    let sel_servico_federal = form_body.children().find("select#sel_servico_federal");
    let te_servfederal = form_body.children().find("textarea#te_servfederal");
    let te_resptribfederal = form_body.children().find("textarea#te_resptribfederal");
    let vl_aliquotaIR = form_body.children().find("input#vl_aliquotaIR");
    let vl_aliquotaINSS = form_body.children().find("input#vl_aliquotaINSS");
    let vl_aliquotaPCC = form_body.children().find("input#vl_aliquotaPCC");
    let dt_inivigencia_federal =form_body.children().find('input#dt_inivigencia_federal')
    let dt_fimvigencia_federal =form_body.children().find('input#dt_fimvigencia_federal')
    let cd_servmuni = form_body.children().find("input#cd_servmuni");
    let te_servMunicipal = form_body.children().find("textarea#te_servMunicipal");
    let vl_aliquota = form_body.children().find("input#vl_aliquota");
    let dt_inivigenciamuni = form_body.children().find('input#dt_inivigenciamuni');
    let dt_fimvigenciamuni = form_body.children().find('input#dt_inivigenciamuni');
    let te_resptributaria = form_body.children().find("textarea#te_resptributaria");
    let form_footer = form_dialog.children().find("div.modal-footer");
    let btn_limpar= form_footer.children().find(".btn#limpar");
    let btn_salvar = form_footer.children().find(".btn#gravar");

    /*
        carregar grupo
    */
    $.get("modules/ler_grupos.php", (data)=> {
        var resp = JSON.parse(data);
        var error = resp.Error;
        var Data = resp.Data;
        var Extra = resp.Extra;
        if (error == '0') {
            sel_grupo.empty();
            
        }
        

    });
});