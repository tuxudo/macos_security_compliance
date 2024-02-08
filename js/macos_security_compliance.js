var formatsecurity_compliance_Compliant = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    if (colvar == "100" || colvar == "100.0"){
        colvar = '<span class="label label-success">100%</span>'
    } else if (colvar >= "90"){
        colvar = '<span class="label label-info">'+colvar+'%</span>'
    } else if (colvar >= "75"){
        colvar = '<span class="label label-warning">'+colvar+'%</span>'
    } else if (colvar < "75"){
        colvar = '<span class="label label-danger">'+colvar+'%</span>'
    } else {
        colvar = colvar
    }
    col.html(colvar)
}

var formatsecurity_compliance_Fails = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    col.html('<span class="label label-danger">'+colvar+'</span>')
}

var formatsecurity_compliance_Passes = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    col.html('<span class="label label-success">'+colvar+'</span>')
}

var formatsecurity_compliance_Exempt = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    col.html('<span class="label label-warning">'+colvar+'</span>')
}

var formatsecurity_compliance_Total = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    col.html('<span class="label label-info">'+colvar+'</span>')
}