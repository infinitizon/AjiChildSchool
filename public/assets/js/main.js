// JavaScript Document

$(function() {
    $("#new_pass").keyup(function() {
        if ($(this).val() != '')
            passwordStrength($(this).val());   //Call the password strength function
    });
    $("#confirm_curr_pass").keyup(function() {
        if ($(this).val() != $("#new_pass").val()) {
            $(".passDesc").html('Mismatch');
            document.getElementById("passStrength").className = "strength1";
        } else {
            passwordStrength($(this).val());
        }
    });
    $('#updt_pass').click(function() {
        var errors = false;
        if (empty($('#curr_pass')))
            errors = true;
        if ($('#new_pass') != $('#confirm_curr_pass'))
            errors = true;
        sendIt(errors)
    });

    ($('#sidebar_left').height() < $('#content').height()) ? $('#sidebar_left').height($('#content').height()) : $('#content').height($('#sidebar_left').height());

    if ($('.bubble')) {
        $('.bubble').tooltip()
    }
    ;
    $('input.reg_course').change(function() {
        name = $(this).attr('code').replace(" ", "_");
        $('#' + name).show().html('');
        if ($(this).is(':checked') && confirm('Do you really want to add this course: ' + $(this).attr('code'))) {
            url = "/students/assets/common/ajax";//URL to pass checked value to on check
            data = "";
            $.ajax({
                "type": "POST", "url": url, "data": $(this).serialize(), "success": function(data) {
                    $('#' + name).html(data).fadeOut(5000);
                }
            });
        }

    });
    $('.print').live('click', function() {
        window.open('/students/print/' + $(this).attr('name'), 'popTest', 'height=500,width=600, scrollbars=1, status=1');
    });
})
function sendIt(error) {
    if (!errors) {
        $('#updt_pass_form').submit();
    }
}