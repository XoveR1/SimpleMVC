$(document).ready(function(){
    $('.add-cl-depend').click(function(){ 
        var form = $(this).parent('.control-group');
        var fieldset = form.parent('fieldset');
        var param = {};
        var csPageKey = form.find('.currentPageKey').attr('value');
        param.valueFrom = form.find('.valueFrom').attr('value');
        param.valueTo = form.find('.valueTo').attr('value');
        param.selectFrom = form.find('.selectFrom').attr('value');
        param.selectTo = form.find('.selectTo').attr('value');
        param.valueCL = form.find('.valueCL').attr('value');
        var item = $( "#confLevelItemTemplate" + csPageKey ).tmpl( param );
        fieldset.find('.control-group').last().after(item.html());
    });
    
    $('.add-limit-month').click(function(){ 
        var form = $(this).parent('.control-group');
        var fieldset = form.parent('fieldset');
        var param = {};
        param.valueFrom = form.find('.valueFrom').attr('value');
        param.valueTo = form.find('.valueTo').attr('value');
        param.valueLimit = form.find('.valueLimit').attr('value');
        var item = $( "#LimitPerMonthItemTemplate" ).tmpl( param );
        fieldset.find('.control-group').last().after(item.html());
    });
    
    $('.add-limit-24h').click(function(){ 
        var form = $(this).parent('.control-group');
        var fieldset = form.parent('fieldset');
        var param = {};
        param.valueFrom = form.find('.valueFrom').attr('value');
        param.valueTo = form.find('.valueTo').attr('value');
        param.valueLimit = form.find('.valueLimit').attr('value');
        var item = $( "#LimitPer24ItemTemplate" ).tmpl( param );
        fieldset.find('.control-group').last().after(item.html());
    });
    
    $('.add-field-button').click(function(){ 
        var form = $(this).parent('.control-group');
        var fieldset = form.parent('fieldset');
        var templateId = $(this).attr('data-template-id');
        var fieldName = '';
        var param = {};
        form.find('.field-data').each(function(){
            fieldName = $(this).attr('data-field-name');
            param[fieldName] = $(this).attr('value');
        });
        var item = $( "#" + templateId ).tmpl( param );
        fieldset.find('.control-group').last().after(item.html());
    });
    
    $('.btn-remove-item').live('click', function(){
        $(this).parent('.control-group').remove();
    });
    
    $('.btn-remove-swim-inline').live('click', function(){
        $(this).parent('.swim-inline').remove();
    });    

    $('.add-county-button').live('click', function(){
        var form = $(this).parent('.control-group');
        var param = {};
        param.filterNum = parseInt(form.find('.currentFilterNum').attr("value"))
        var item = $( "#countryFieldTemplate" ).tmpl( param );
        form.find('.swim-inline:last').after(item.html());
        addCountryAutocomplite(form.find('.swim-inline:last input[type="text"]'));
    });
    
    $('.add-counties-filter-button').click(function(){
        var form = $(this).parent('.control-group');
        var fieldset = form.parent('fieldset');
        var param = {};
        var nextFilterNum = parseInt($('#CountryFiltersCounter').attr('value')) + 1;
        param.Countries = [];
        param.filterNum = nextFilterNum;
        form.find('.swim-inline .field-data').each(function(index, value){
            param.Countries.push($(value).attr('value'));
            if(index > 0){
                $(value).parent('.swim-inline').remove();
            } else {
                $(value).attr('value', '');
            }
        });
        param.confLevel = form.find('.confLevel').attr('value');
        form.find('.confLevel').attr('value', '');
        var item = $( "#countriesFilterTemplate" ).tmpl( param );
        fieldset.find('.control-group').last().after(item.html());
        $('#CountryFiltersCounter').attr('value', nextFilterNum);
        addCountryAutocomplite();
    });
    
    $('.add-age-host-depend').live('click', function(){
        var workBlock = $(this).parents('.host-group').find('.ageDependList');
        var param = {};
        var countAgeFilters = workBlock.find('.control-group').length - 1;
        param.valueFrom = workBlock.find('.valueFrom').attr('value');
        param.selectFrom = workBlock.find('.selectFrom').attr('value');
        param.valueTo = workBlock.find('.valueTo').attr('value');
        param.selectTo = workBlock.find('.selectTo').attr('value');
        param.valueCL = workBlock.find('.valueCL').attr('value');
        if(countAgeFilters < 1){
            param.requriedLine = true;
        } else {
            param.requriedLine = false;
        }
        var item = $( "#ageDependItemTemplate" ).tmpl( param );
        workBlock.find('.control-group').last().after(item.html());
        //$(this).attr('disabled', true);
    });
    
    
    $('.add-host-filter-button').live('click', function(){
        var workBlock = $(this).parents('.host-group');
        var ageFilterBlock = workBlock.find('.ageDependList');
        var param = {};
        param.hostName = workBlock.find('.field-data').attr('value');
        var filterIndex = $('.host-group').length - 1;
        var ageFilterList = new Array();
        var countAgeFilters = 0;
        ageFilterBlock.find('.control-group:not(:first)').each(function(){
            var filterItem = {};
            filterItem.valueFrom = $(this).find('.valueFrom').attr('value');
            filterItem.selectFrom = $(this).find('.selectFrom').attr('value');
            filterItem.valueTo = $(this).find('.valueTo').attr('value');
            filterItem.selectTo = $(this).find('.selectTo').attr('value');
            filterItem.valueCL = $(this).find('.valueCL').attr('value');
            filterItem.filterIndex = filterIndex;
            if(countAgeFilters < 1){
                filterItem.requriedLine = true;
            } else {
                filterItem.requriedLine = false;
            }
            countAgeFilters++;
            ageFilterList.push(filterItem);
        });       
        param.ageFilterList = ageFilterList;
        var item = $( "#hostFilterTemplate" ).tmpl( param );
        workBlock.parent().find('.host-group').last().after(item.html());
    });
    
    $('.remove-host-filter-button').live('click', function(){
        $(this).parents('.host-group').remove();
    });
    
    $('input').tooltip({
        'trigger' : 'focus'
    });
    
    var currentUrl = window.location.href;
    var sharpPosition = currentUrl.indexOf('#!');
    var activeTabId = '';
    if(sharpPosition != -1){
        activeTabId = currentUrl.substring(sharpPosition + 2);
        currentUrl = currentUrl.substring(0, sharpPosition);
    } else {
        activeTabId = $('.nav-tabs li:first a').attr('href').substring(1);
    }
    $('input[name="activeTabId"]').attr('value', activeTabId);
    $('.nav-tabs a[href="#' + activeTabId + '"]').tab('show');
    
    $('.nav-tabs a').click(function(e){
        e.preventDefault();
        $(this).tab('show');
        var href = $(this).attr('href');
        var tabId = href.substring(1, href.length);
        href = '#!' + tabId;
        window.location.href = currentUrl + href;
        $('input[name="activeTabId"]').attr('value', tabId);
    });
      
    if($(".country-autocomplete").length){
        addCountryAutocomplite();
    }
})

function noValidField(field){
    var errorTabId = $(field).parents('.tab-pane').attr('id');
    var errorTab = $('.nav-tabs a[href="#' + errorTabId + '"]');
    errorTab.tab('show');
}

function addCountryAutocomplite(object){
    var autoCompliteObject;
    if(object == null){
        object = ".country-autocomplete";
    }
    if(typeof(object) == 'string'){
        autoCompliteObject = $(object);
    }
    if(typeof(object) == 'object'){
        autoCompliteObject = object;
    }
    autoCompliteObject.autocomplete({
        minLength: 2,
        source: function( request, response ) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }

            lastXhr = $.getJSON( BASE_URI + "config/countries/", request, function( data, status, xhr ) {
                cache[ term ] = data;
                if ( xhr === lastXhr ) {
                    response( data );
                }
            });
        }
    });
}

