$(document).ready(function() {
    $('#sorttable').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": BASE_URI + "users/list/",
        "fnServerData": fnDataTablesPipeline,
        "aaSorting": [[ 1, "desc" ]],
        "bStateSave": true,
        "sPaginationType": "full_numbers",
        "fnInitComplete" : initTable,
        "fnRowCallback": fnShowInTmpl
    } ); 
    
    $('.ip-info').live('click', function(e){
        e.preventDefault();
        var ip = $(this).text().trim();
        var url = BASE_URI + 'users/ipinfo/' + ip;
        $.getJSON(url, function(data){
            data.ip = ip;
            showIpInfoPage(data);
        });
    }); 
} );


