function formatDate(data) {
    var dt_ret='';
    if (data !== null && data !== undefined) 
        if (data.length > 0) {
            var aux = data.split(" ");
            if (aux.length > 1) {
                var dt=aux[0];
                var hr=aux[1];
            } else {
                var dt=aux[0];
                var hr='';
            }
            var aux = dt.split("-");
            var ano = aux[0];
            var mes = aux[1];
            var dia = aux[2];
            dt_ret = dia+"/"+mes+'/'+ano+ ' '+hr;
        }
    return dt_ret;
}


function formatTitle (ctitle) {
    if (ctitle.length > 0) {
        var aux = ctitle.split(",");
        ctitle = aux.join("\n");
    }
    return ctitle;
}

function soundex(name) {
    let s = [];
    let si = 1;
    let c;

    //              ABCDEFGHIJKLMNOPQRSTUVWXYZ
    let mappings = "01230120022455012623010202";
  
    s[0] = name[0].toUpperCase();

    for(let i = 1, l = name.length; i < l; i++) {
       c = (name[i].toUpperCase()).charCodeAt(0) - 65;
       if(c >= 0 && c <= 25) {
             if(mappings[c] != '0') {
                if(mappings[c] != s[si-1]) {
                   s[si] = mappings[c];
                   si++;
                }

                if(si > 3) {
                   break;
                }
             }
       }
    }

    if(si <= 3) {
       while(si <= 3) {
             s[si] = '0';
             si++;
       }
    }

    return s.join("");
    }
    
    function mascara (doc, mask) {
        if (doc.length > 0) {
            var pos = 0;
            var new_doc = "";
            for (var i=0; i < mask.length; i++) {
                var cdig = mask[i];
                if (isCharNumber(cdig)) {
                    new_doc += doc[pos];
                    pos+=1;
                } else {
                    new_doc += mask[i];
                }
            }
            return new_doc;
        } else {
            return doc;
        }
    }

    Date.prototype.addDays = function(days) {
        if (days.length > 0 || days !== undefined) {
            this.setDate(this.getDate() + parseInt(days));
        }
        return this;
    }; 
        
    var formatCurrency = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2
    });
    
    function CurrencyToFloat (value) {
        var retValue = 0.0;
        if (typeof value === 'string') {
            if (value.length > 0) {
                if (value.indexOf(',') > 0) {
                    var vaux = value.split(",");
                    if (vaux.length > 1) {
                        var inteiro = vaux[0].replace(".", "");
                        var decimal = vaux[1];
                    } else {
                        var inteiro = vaux[0].replace('.', "");
                        var decimal = "00";
                    }
                } else {
                    var inteiro = value.replace(".", "");
                    var decimal = "00";
                }
                if (decimal.length  == "2") {
                    retValue = inteiro+'.'+decimal;
                } else {
                    retValue = inteiro+'.'+decimal+"0";
                }
                retValue = parseFloat(retValue)    
            } 
            
        } else if (typeof value == "float" || typeof value == "integer") {
            retValue = value;
        }
        return retValue;
    }