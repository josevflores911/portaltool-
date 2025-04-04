$(document).ready((evt) => {
    var id_user = window.localStorage.getItem('id_user');
    var id_nota = window.localStorage.getItem("id_nota");
    var nu_nota = window.localStorage.getItem("nu_nota");
    var module = window.localStorage.getItem("module");
    var baliquotas = false;
    var pdata_idnota = "idnota";
    var pbody_table = "tbody_service";
    id_user = parseInt(id_user);
    id_nota = parseInt(id_nota);

    if (module == "ntservicos") {
        var nm_module = "modules/ler_logs.php";
    } else if (module == "ntconsumo") {
        var nm_module = "modules/ler_logsntconsumo.php";
    } else if (module == "usuarios") {
        pdata_idnota="id";
        pbody_service = "tbody_usuarios";
        var h4 = $(".modal-header").children().find("h4");
        var html = h4.html();
        html = html.replace("da nota", "do usuário");
        h4.html("");
        h4.html(html);
        var nm_module = "modules/ler_logusuarios.php";

    } else if (module == "ntsefaz") {
        var nm_module = "modules/ler_logntsefaz.php";
    } else if (module == "ntaliquotas") {
        var id_servicoxgrupo = window.localStorage.getItem("id_servicoxgrupo");
        baliquotas = true;
    }

    if (! baliquotas) {
        id_nota = parseInt(id_nota);
    
        // captura o número da nota
        let modal = $(".modal");
        let gener = modal.parents("div.generic")
        parent_tbody =gener.parents('div.principal').find(`table > tbody#${pbody_table}`);
        if (parent_tbody) {
            let vtr = parent_tbody.find("tr");
            let curr_tr = vtr.filter((ix,elem) => {
                var tds = $(elem).children("td");
                var id = tds.eq(0).data(`${pdata_idnota}`);
                return id == id_nota;
            });
            if (module == "ntservicos") {
                var nu_nota = curr_tr.children("td").eq(3).text();
            }
        }
    
        let total_records = 0;
        let total_pages = 0;
        let lbl_nota = $(".modal.fade#show-log").children().find(".lbl_nota");
        lbl_nota.html(nu_nota);
        let table_log = $(".modal.fade#show-log").children().find("table.tb-log");
        let tbody = $(".modal.fade#show-log").children().find("table.tb-log > tbody");
        let tfoot = $(".modal.fade#show-log").children().find("table.tb-log > tfoot");
    
        let sel_rows = tfoot.children().find("#sel-linhas");
        let sel_page = tfoot.children().find("#sel-page");
        var npage = parseInt(sel_page.val());
        var nrows = parseInt(sel_rows.val());
        let waiting = $(".modal.fade#show-log").children().find(".waiting-log")
    
        // navegar

        sel_page.on("keypress", (evt) => {
            var ch = evt.which || evt.charCode;
            var bret = false;
            if (ch < 48 ) {
                if (ch == 9 || ch == 13 || ch == 8 || ch ==  46 || ch == 44 || ch == 27) {
                    bret = true;
                }
            } else if (ch > 57) {
                if (ch >= 96 && ch <= 105) 
                    bret= true;
            } else if ( ch >= 48 && ch <= 57)  {
                    bret= true;
            }
            return bret;
        });

        let imgs = tfoot.children().find("img");

        imgs.on("click", (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            var obj = $(evt.target);
            let opage = obj.parents().find("#sel-page");
            let orows = obj.parents().find("#sel-linhas");
            npage = parseInt(opage.val());
            
            nrows = orows.val();
            var id = obj.attr("id");

            switch (id) {
                case "first":
                    npage = 1;
                    break;

                case "prev":
                    npage -=1;
                    if (npage == 0) npage =1;
                    break;

                case "next":
                    npage +=1;
                    if (npage > total_pages) npage = total_pages;
                    break;

                case "last":
                    npage = total_pages;
                break;
            }
            sel_page.val(npage);
            waiting.css("display", 'block');
            if (module == "usuarios") {
                id_nota = id_user;
            }
            paginar(id_nota, nrows, npage);
        }); 
         
        sel_rows.on("change", (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            var obj = $(evt.target);
            nrows = parseInt(obj.val());
            let opage = obj.parents().find("#sel-page");
            npage = parseInt(opage.val());
            waiting.css("display", 'block');
            paginar (id_nota, nrows, npage);
        });
        // captura o número da nota

        paginar(id_nota, nrows, npage);
   

        function paginar (id_nota, nrows,npage) {
            var parms = {
                id_nota: id_nota,
                nu_rows: nrows,
                curr_page: npage
            };
            
            $.get(nm_module, parms, (data) => {
                var resp = JSON.parse(data);
                if (resp.Error == '0') {
                    var clinha ="";
                    
                    for (const [key, row] of Object.entries(resp)) {
                        
                        if (key == "Error") continue;
                        if (key == "total_records") {
                            total_records = parseInt(row);
                            if (nrows == 0) {
                                nrows = 20;
                            }
                            total_pages = parseInt(total_records / nrows);
                         
                            if ((total_records % nrows) !== 0) total_pages +=1;
                            if (total_pages == 0) {
                                total_pages = 1;
                            }
                            continue;
                        }
                        var nm_user = format_descr(row.nm_user,22);
                        var dt_log = row.dt_log;
                        var nm_table = row.nm_table;
                        var te_operacao = row.te_operacao;
                        var te_descr = (row.Te_Descricao) ? row.Te_Descricao : "";
                        var Te_descricao = format_descr(te_descr,30);
                        var data = new Date(dt_log);
                        data = data.toLocaleDateString();
                        var line = `<tr><td>${nm_user}</td>`;
                        line += `<td>${dt_log}</td>`;
                        line += `<td>${te_operacao}</td>`;
                        line += `<td>${nm_table}</td>`;
                        line += `<td title='${te_descr}'>${Te_descricao}</td></tr>`;
                        clinha += line;
                    }
                    if (resp.length < nrows) {
                        var dif = nrows - resp.length;
                        for (var i = 0; i < dif; i++) {
                            var line = "<tr class='justify-content-between'>";
                            line += "<td class='br-1'>&nbsp;</td>";
                            line += "<td class='br-2'>&nbsp;</td>";
                            line += "<td class='br-3'>&nbsp;</td>";
                            line += "<td class='br-4'>&nbsp;</td>";
                            line += "<td class='br-5'>&nbsp;</td></tr>";
                            clinha += line;
                        }
                    }
    
                    tbody.html("");
                    tbody.html(clinha);
                    waiting.css("display", "none");
                }
    
                function format_descr (descr,num) {
                    var vresult = [];
                    var i= 0;
                    while (i < descr.length) {
                        var aux = descr.substr(i, i+num);
                        i += num;
                        vresult.push(aux);
                    }
                    return vresult.join("<br>");
                } 
            }); 
        }
    }
    


});