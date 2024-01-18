<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="macos-security-compliance-percent-widget">
        <div class="panel-heading" data-container="body">
            <h3 class="panel-title"><i class="fa fa-link"></i>
                <span data-i18n="macos_security_compliance.macos_security_compliance"></span> 
                <list-link data-url="/show/listing/macos_security_compliance/macos_security_compliance"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    var body = $('#macos-security-compliance-percent-widget div.panel-body');

    $.getJSON( appUrl + '/module/macos_security_compliance/get_compliance_stats', function( data ) {

        // Clear previous content
        body.empty();

        var entries = [
            {name: '< 75%', link: '', count: 0, class:'btn-danger', id: 'danger'},
            {name: '75% +', link: '', count: 0, class:'btn-warning', id: 'warning'},
            {name: '90% +', link: '', count: 0, class:'btn-info', id: 'info'},
            {name: '100%', link: '100%', count: 0, class:'btn-success', id: 'success'}
        ]

        // Calculate entries
        if(data.length){

            // Add count to entries
            $.each(entries, function(i, o){
                o.count = data[0][o.id];
            })

            // Render entries
            $.each(entries, function(i, o){
                // Set blocks, disable if zero
                if(o.count != "0"){
                    body.append('<a href="'+appUrl+'/show/listing/macos_security_compliance/macos_security_compliance/#'+encodeURIComponent(o.link)+'" class="btn '+o.class+'"><span class="bigger-150">'+o.count+'</span><br>'+o.name+'</a> ');
                } else {
                    body.append('<a href="'+appUrl+'/show/listing/macos_security_compliance/macos_security_compliance/#'+encodeURIComponent(o.link)+'" class="btn '+o.class+' disabled"><span class="bigger-150">'+o.count+'</span><br>'+o.name+'</a> ');
                }
            });
        }
        else{
            body.append(i18n.t('no_clients'));
        }
c});
});
</script>
