$(document).ready(function() {
    $('#avatarAssocInfo').treeview({
        persist: "cookie"
    });
    $('#showNotesModal form').submit(function(){
        var action = $(this).attr('action');
        var noteText = $('.noteText').val();
        $.post(action, {note: noteText}, function(data){
            noteText = '<div class="listItem"><b>' + data.date + '</b> ' + data.note + '</div>';
            var firstRecord = $('.listNotes .listItem:first-child');
            if(firstRecord.length){
                firstRecord.prepend(noteText);
            } else {
                $('.listNotes').html(noteText);
            }
            $('.noteText').val('');
        }, 'json');
        return false;
    });
});


