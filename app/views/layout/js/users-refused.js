$(document).ready(function() {
    var sAjaxSource = BASE_URI + "users/rlist/";
    if($('.filterParams').length){
        sAjaxSource += $('.filterParams').attr('value');
    }
    $('#sorttable').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": sAjaxSource,
        "fnServerData": fnDataTablesPipeline,
        "aaSorting": [[ 1, "desc" ]],
        "bStateSave": true,
        "sPaginationType": "full_numbers",
        "fnInitComplete" : initTable,
        "fnRowCallback": fnShowInTmpl
    } ); 
    
    var reportCache = {};
        
    var showPopup = function(data){
        reportCache[data.att_id] = data;
        var item = $( "#reportModalTemplate" ).tmpl(data);
        $('#reportModal').html(item.html());
        $('#reportModal').modal('show');
    };
    
    $('.popup-report').live('click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var recordId = url.substring(url.lastIndexOf('/') + 1);
        if(!reportCache[recordId]){
            $.getJSON(url, showPopup);
        } else {
            showPopup(reportCache[recordId]);
        }         
    });
       
} );


