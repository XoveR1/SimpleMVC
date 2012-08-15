$(document).ready(function() {
    var sAjaxSource = BASE_URI + "users/alist/";
    if($('.filterParams').length){
        sAjaxSource += $('.filterParams').attr('value');
    }
    $('#sorttable').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": sAjaxSource,
        "fnServerData": fnDataTablesPipeline,
        "bSort" : false,
        "aaSorting": [[ 1, "desc" ]],
        "bStateSave": true,
        "sPaginationType": "full_numbers",
        "fnInitComplete" : initTable,
        "fnRowCallback": fnShowInTmpl
    } ); 
} );


