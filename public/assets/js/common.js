// JavaScript Document

$(function() {
    //
    if ($.fn.editable) {
        $.fn.editable.defaults.mode = 'inline'
        $('.txtEdit').editable({
            type: 'text', url: '/students/assets/common/ajax'
        });
        $('.txtAreaEdit').editable({
            type: 'textarea', url: '/students/assets/common/ajax'
        });
    }
    $('table.my_tables tr:even').addClass('alt');
    $("table.my_tables tr").mouseover(function() {
        $(this).addClass("over");
    }).mouseout(function() {
        $(this).removeClass("over");
    });
    $("a.delete").live("click", function(event) {
        return confirm($(this).attr('data'));
    });
    //Close any notification area showing
    setTimeout(closeNotification, 5000);
    $("div#notification .close").click(function() {
        closeNotification();
    });
});
var closeNotification = function() {
    if ($('div#notification').css('display') == 'block')
        $('div#notification').slideUp('slow');
}

$.urlParam = function(url, name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
    if (results === null) {
        return null;
    } else {
        return results[1] || 0;
    }
};
