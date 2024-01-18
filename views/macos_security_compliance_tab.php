<div id="macos_security_compliance-tab"></div>
<h2 data-i18n="macos_security_compliance.security_compliance"></h2>

<div id="macos_security_compliance-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/macos_security_compliance/get_tab_data/' + serialNumber, function(data){
        if( ! data || data.length == 0){
            // Change loading message to no data
            $('#macos_security_compliance-msg').text(i18n.t('no_data'));

        } else {

            // Hide loading/no data message
            $('#macos_security_compliance-msg').text('');

            // Update the tab badge count
            $('#macos_security_compliance-cnt').text("");

            var clientDetail = "";
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                var rows_rules = []
                for (var prop in d){
                    if ((d[prop] == '' || d[prop] == null || d[prop] == "none" || prop == '') && d[prop] != 0){
                       // Do nothing for empty values to blank them

                    // Add proper label to compliance
                    } else if(prop == 'compliant' && d[prop] == 100){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-success">'+d[prop]+'%</span></td></tr>';
                        $('#macos_security_compliance-cnt').text(d[prop]+"%").addClass('alert-success');
                    } else if(prop == 'compliant' && d[prop] >= 90){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-info">'+d[prop]+'%</span></td></tr>';
                        $('#macos_security_compliance-cnt').text(d[prop]+"%");
                    } else if(prop == 'compliant' && d[prop] >= 75){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-warning">'+d[prop]+'%</span></td></tr>';
                        $('#macos_security_compliance-cnt').text(d[prop]+"%");
                    } else if(prop == 'compliant' && d[prop] < 75){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-danger">'+d[prop]+'%</span></td></tr>';
                        $('#macos_security_compliance-cnt').text(d[prop]+"%");

                    // Add label to results
                    } else if(prop == 'fails'){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-danger">'+d[prop]+'</span></td></tr>';
                    } else if(prop == 'passes'){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-success">'+d[prop]+'</span></td></tr>';
                    } else if(prop == 'exempt'){
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span class="label label-warning">'+d[prop]+'</span></td></tr>';

                    } else if(prop == "last_compliance_check" && d[prop] > 100){
                        var date = new Date(d[prop] * 1000);
                        rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td><span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span></td></tr>';

                    // Process compliance JSON
                    } else if(prop == "compliance_json"){

                        // Process rules json for processing in the fancy table
                        var rules_data = JSON.parse(d[prop]);

                        for (rule_entry in rules_data){

                            rule_row = []
                            rule_row['name'] = rule_entry
                            rule_row['title'] = rule_entry

                            // Process compliance findings
                            if (rules_data[rule_entry]["finding"]){
                                rule_row['compliance'] = "0" // True is non-compliant
                            } else {
                                rule_row['compliance'] = "1" // # False is compliant
                            }

                            // Process exemptions
                            if (rules_data[rule_entry]["exempt"] !== undefined){
                                rule_row["exempt"] = "1"
                            } else {
                                rule_row["exempt"] = ""
                            }

                            // Process exempt reasons
                            if (rules_data[rule_entry]["exempt_reason"] !== undefined){
                                rule_row["exempt_reason"] = rules_data[rule_entry]["exempt_reason"]
                            } else {
                                rule_row["exempt_reason"] = ""
                            }

                            rows_rules.push(rule_row)
                        }

                    } else {
                       rows = rows + '<tr><th>'+i18n.t('macos_security_compliance.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
                }

                // Generate table
                $('#macos_security_compliance-tab')
                    .append($('<div style="max-width:550px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))

                // Generate table
                if ( rows_rules !== ""){
                    $('#macos_security_compliance-tab')
                        .append('<div id="macos_security_compliance-table-view" class="row" style="padding-left: 15px; padding-right: 15px;"><h4>'+i18n.t('macos_security_compliance.rules')+'</h4><table class="table table-striped table-condensed table-bordered" id="macos_security_compliance-table"><thead><tr><th data-colname="macos_security_compliance.name">'+i18n.t('macos_security_compliance.rule')+'</th><th data-colname="macos_security_compliance.title">'+i18n.t('macos_security_compliance.title')+'</th><th data-colname="macos_security_compliance.compliance">'+i18n.t('macos_security_compliance.compliant')+'</th><th data-colname="macos_security_compliance.exempt">'+i18n.t('macos_security_compliance.exempted')+'</th><th data-colname="macos_security_compliance.exempt_reason">'+i18n.t('macos_security_compliance.exempt_reason')+'</th></tr></thead><tbody><tr><td data-i18n="listing.loading" colspan="4" class="dataTables_empty"></td></tr></tbody></table></div>')

                        var table_data = rows_rules;
                        // var table_data = JSON.parse(data[0]["known_networks"]);
                        $('#macos_security_compliance-table').DataTable({

                            data: table_data,
                            order: [[0,'asc']],
                            autoWidth: false,
                            columns: [
                                { data: 'name' },
                                { data: 'title' },
                                { data: 'compliance' },
                                { data: 'exempt' },
                                { data: 'exempt_reason' }
                            ],
                            createdRow: function( nRow, aData, iDataIndex ) {

                                // Format rule title
                                var colvar=$('td:eq(1)', nRow).html();
                                if (colvar !== ""){
                                    var title = i18n.t('macos_security_compliance.rule_title.'+colvar);
                                    if (!title.includes("macos_security_compliance.rule_title.")){
                                        $('td:eq(1)', nRow).text(i18n.t('macos_security_compliance.rule_title.'+colvar))
                                    } else {
                                        $('td:eq(1)', nRow).text('')
                                    }
                                }

                                // Format compliance
                                var colvar=$('td:eq(2)', nRow).html();
                                colvar = colvar == '1' ? '<span class="label label-success">'+i18n.t('yes')+'</span>' :
                                (colvar === '0' ? '<span class="label label-danger">'+i18n.t('no')+'</span>' : '')
                                $('td:eq(2)', nRow).html(colvar)

                                // Format exempt
                                var colvar=$('td:eq(3)', nRow).html();
                                colvar = colvar == '1' ? '<span class="label label-warning">'+i18n.t('yes')+'</span>' : ''
                                $('td:eq(3)', nRow).html(colvar)
                            }
                    });
                }
            })
        }
    });
});
</script>
