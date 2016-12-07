/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    $('.fa-user').on('click', function(e) {
        e.preventDefault();
        $('div#user_dets').toggle();
    });
    $('.fa-power-off').on('click', function(e) {
        $('div#user_dets').hide();
    });
    $(document).mouseup(function(e) {
        var container = $("div#user_dets");
        if (!container.is(e.target) && container.has(e.target).length === 0) { // if the target of the click isn't the container nor a descendant of the container
            container.hide();
        }
    });
    
    if ($.fn.editable) {
        $.fn.editable.defaults.mode = 'inline'
        $('.txtEdit').editable({
            type: 'text', url: '/students/assets/common/ajax'
        });
        $('.txtAreaEdit').editable({
            type: 'textarea', url: '/students/assets/common/ajax'
        });
    }
    $( "#datepicker,.datePicker" )
            .datepicker({dateFormat: 'dd-M-yy',yearRange: "-50:+50",changeMonth: true, changeYear: true,});
    
    $("table.my_tables tr:even").addClass('alt');
    $("table.my_tables tr").mouseover(function() {
        $(this).addClass("over");
    }).mouseout(function() {
        $(this).removeClass("over");
    });
    if ($("table.my_tables").hasClass('vertColNm')) {
        $("table.my_tables").find('td:first-child').addClass('vertColNm');
    }
    $("tr:contains('Submitted')").find("input[type=checkbox]").attr("disabled", "disabled");
    
    $("a.editPerson").on("click", function(e){
        e.preventDefault();
        if($(this).hasClass('delete')){
            if(confirm("Are you sure you want to delete this entry"))
                window.location.href = $(this).attr('href') + ":" +$(this).attr('key');
            return;
        }
        window.location.href = $(this).attr('href') + ":" +$(this).attr('key')
    });
    $('table.classes tr:gt(0) td:not(:last-child)').on('click', function() {
        $key = $(this).parents('tr').find("a.edit").attr('key');
        $data = {getClassKeys: $key};
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                data = $.parseJSON(data);
                $class_subject_id = [];
                
                $("select[name^=subjects]").find("option").attr("selected", false);
                $("select[name^=teacher]").find("option").attr("selected", false);
                $("input[name=class_subject_id]").val('Submit');
                
                $.each (data, function (bb) {
                    $class_subject_id.push( data[bb].class_subject_id);
                    $("select[name^=subjects] option[value='" + data[bb].subject_id + "']").attr("selected", true);
                    $("select[name^=teacher] option[value='" + data[bb].teacher_id + "']").attr("selected", true);
                    $("input[name=class_subject_id]").val($class_subject_id);
                    $("input[name=submitSbjt]").val("Update");
                });
            }
        });
    }).css({'cursor':'pointer'});
        
    $('input[name=submitSbjt]').on('click', function(e) {
        e.preventDefault();e.stopPropagation();
        $submit = $(this);
        $submit.prop('disabled', true).removeClass('button').val('Working...');
        $checked = [];
        $('table.classes').find('input[type="checkbox"]:checked').each(function (i,k) {
             $checked[i] = $(this).parents('tr').find("a.edit").attr('key');
        });
        if($checked.length === 0) {
            alert("To update or Submit a teacher class relationship, you must select the class to work with");
            $submit.prop('disabled', false).addClass('button').val('Submit');
            return;
        }
        if($(".class_nm option:selected").length ==0) {
            alert("At least a subject must be selected to proceed");
            $submit.prop('disabled', false).addClass('button').val('Submit');
            return;
        }
        $data = $(this).parent('form').serialize() + '&' + $.param({'checked': $checked});
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                data = $.parseJSON(data);
                $submit.prop('disabled', false).addClass('button').val('Submit');
                alert(data.msg);
            }
        });
    });
/*** 
 * Define Exam Page
 */
    $('select[name=exam_teacher_class]').on('change', function(e) {
        e.preventDefault();e.stopPropagation();
        $data = $(this).serialize();
        $("select[name=pExam_sessions]").find('option').remove().end().append('<option>-- Loading... --</option>');
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $("select[name=pExam_sessions]").find('option').remove().end().append('<option>-- Select Session --</option>');
                data = $.parseJSON(data);
                $.each(data, function(i, item) {
                    $("select[name=pExam_sessions]").append('<option value="'+item.val_id+'">'+item.val_dsc+'</option>');
                });
//                $("#popStudExams").html(data);
//                $("select[name=pExam_sessions]").val( $(data).find('#currSession').val() );
            }
        });
    });
    $('select[name=pExam_sessions]').on('change', function(e) {
        e.preventDefault();e.stopPropagation();
        $data = $.param({'pExam_sessions': $('select[name=exam_teacher_class]').val(),'selectedSesn':$(this).val()}); 
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $("#popStudExams").html(data);
            }
        });
        
    });
    /** Describes what happens when we submitExam */
    $(document).on("click", "a.edtStudExam", function(e){
        e.preventDefault();e.stopPropagation();
        $class = $(this).find('.fa-check').removeClass('fa-check').addClass('fa-spinner fa-spin')
        $data = '';
        $(this).parents('tr').find('input, select, textarea').each(function(){
            $data += ($data) ? '&'+$(this).serialize() : $(this).serialize();
        });
        $data = $data  + '&' + $.param({'class_id': $(".exam_teacher_class").val(), 'sesssion_id': $(".pExam_sessions").val()});
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                data = $.parseJSON(data);
                if(data.result){
                    $class.removeClass('fa-spinner fa-spin').addClass('fa-check')
                    alert(data.msg);
                }
            }
        });
    });
/*** 
 * End: Define Exam Page
 */    
/** LOV page ***/
    $('i.addToDef').on('click', function(e) {
        e.preventDefault();e.stopPropagation();
        $('div.addDef').toggle();
        $(this).toggleClass('fa-plus-circle fa-minus-circle');
    }).hover(function(){
        $(this).css({'cursor':'pointer'});
    });
    $('i.saveDef').on('click', function(e) {
        e.preventDefault();e.stopPropagation();
        $data = $(this).parents('form').serialize();
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                data = $.parseJSON(data);
                if(data.result == 'Failure'){
                    alert(data.msg);
                }else{
                    $('select[name=definitions]').append($('<option/>', { 
                        value: data.def_id,text : data.val_desc 
                    }));
                }
            }
        });
    }).hover(function(){
        $(this).css({'cursor':'pointer'});
    });
    $(document).on("click", "a.printExam4Stud", function(e){
        e.preventDefault();e.stopPropagation();
        $data = $.param({printStud:1, class:$('select[name=exam_teacher_class]').val(), curr_sess:$('select[name=pExam_sessions]').val(), stud:$(this).attr('key')});
        var formWidth = null;
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html($(data));
                formWidth = $(data).filter("div#stud_sheet").attr('width');
                $( "#alerts" ).dialog({
                    width: 900
                }); 
            }
        });
    });
    
    $('select[name=definitions]').on('change', function(e) {
        e.preventDefault();e.stopPropagation();
        $data = $(this).serialize();
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $("#popDefinitions").html(data);
            }
        });
    });
    $(document).on("click", "a.definitions", function(e) {
        e.preventDefault();e.stopPropagation();
        $data = this.search.split('?')[1];
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $("#popDefinitions").html(data);
            }
        });
    });
/** End: LOV page ***/
    $(document).on("click", "a.editDef", function(e){
        e.preventDefault();e.stopPropagation();
        $data = $(this).parents('tr').find('input, select, textarea').serialize();
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html(data).dialog({
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    })
            }
        });
    });
    $(document).on("click", "a.newDef", function(e){
        e.preventDefault();e.stopPropagation();
        $data = $.param({'newLov': 1});
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html(data).dialog({
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    })
                $('select[name=defs]').val($('select[name=definitions]').val())
            }
        });
    });
    $(document).on("click", "#submitLov", function(e){
        e.preventDefault();e.stopPropagation();
        $data = $(this).parents('form').serialize();
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html(data).dialog({
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    })
            }
        });
    });
    //For New Exams
    $(document).on("click", "a.newExamDef", function(e){
        e.preventDefault();e.stopPropagation();
        if($(this).hasClass('delete')){
            if(confirm("Are you sure you want to delete this entry. Deleting simply makes it invisible to users!"))
                window.location.href = $(this).attr('href') + ":" +$(this).attr('key');
            return;
        }
        $data = $.param({'newExamDef': 1, exam_type_id:$(this).attr('key')});
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html(data).dialog({
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    })
            }
        });
    });
    //For New sessions
    $(document).on("click", "a.newSessionDef", function(e){
        e.preventDefault();e.stopPropagation();
        if($(this).hasClass('delete')){
            if(confirm("Are you sure you want to delete this entry. Action is irreversible!"))
                window.location.href = $(this).attr('href') + ":" +$(this).attr('key');
            return;
        }
        $data = $.param({'newSessionDef': 1, session_term_id:$(this).attr('key')});
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html(data).dialog({
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    })
            }
        });
    });
    $(document).on("click", "input[name=submitSession],input[name=submitExam]", function(e){
        e.preventDefault();e.stopPropagation();
        $data = $(this).parents('form').serialize() + '&' +$(this).attr('name') +'='+ $(this).val();
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $( "#alerts" ).html(data).dialog({
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    })
            }
        });
    });
    $(document).on("click", "div#stud_sheet .finalPrint", function(e){
        document.title = "Child School Report Sheet"; 
        window.print();
    });
});
/****
 * This relates to plotting of graphs
 */
    function prev_friday(dateAndTime) {
        var t = dateAndTime.split(/[- :]/);
        var today = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]), friday, day, closest;

        if(today.getDay() == 5){
            return today.getFullYear() + "/" + (today.getMonth() + 1) + "/" + today.getDate() + " " + t[3] + ":" + t[4]+ ":" +t[5];
        }else{
            day = today.getDay();
            lastweekFrmToday = (today.getDate()-7);
            friday = lastweekFrmToday - day + (day === 0 ? -6 : 5);
        }
        closest = new Date(today.setDate(friday)); 

        return closest.getFullYear() + "/" + (closest.getMonth() + 1) + "/" + closest.getDate()+ " " + t[3] + ":" + t[4]+ ":" +t[5];
    }
    $(document).bind('click', function(ev) {
  		if ( !$(ev.target).is("canvas")) {
        	$("ul.contextMenu").css({display:'none'});
    	}
  	});
    
    $.jqplot.config.enablePlugins = true;
    var callJqPlotPieChat = function(which, dTitle, data) {
        var pieCharts = jQuery.jqplot(which, [data], {
            title: dTitle,
            seriesColors: [ "#090", "#F00", "#FFA500"],
            captureRightClick: true,
            seriesDefaults: {
                // Make this a pie chart.
                renderer: jQuery.jqplot.PieRenderer,
                rendererOptions: {
                    // Put data labels on the pie slices.
                    // By default, labels show the percentage of the slice.
                    showDataLabels: true,
                    
                    //This will highlight a slice on mouse down instead of on move over
                    //highlightMouseDown: true 
                }
            },
            legend: {show: true, location: 's'}
        });
        $('#' + which).bind('jqplotDataClick', function(ev, seriesIndex, pointIndex, data) {
        	if (!$(ev.target).is(".contextMenu")) {
        		$("ul.contextMenu").css({display:'none'});
    		}
            var data = $('#' + which + 'Form').serialize() + '&' + $.param({chartTp: which + '-' + data});
            $.ajax({
                "type": "POST", "url": "assets/common/report.acc.inc.php", "data": data, "success": function(data) {
                    $('.migrate').html(data);$('fieldset .overlay').hide();
                    zebraTable();
                    dashboardPaging();
                    clickRowMinusCheck();
                }
                , beforeSend: function() {
                    $('.migrate').html('<div style="font-weight:bolder;margin:5% 10%;text-align:center;"><img src="/cleanup/assets/images/loading.gif" /><br />Loading...</div>');
                }
            });
        });
        $('#' + which).bind('jqplotRightClick', function (ev, seriesIndex, pointIndex, data) {        
            $("ul.contextMenu").css({display:'none'});
            if (data !== null) {
            	var data = $.param({chartTp: which + '-' + data.data});
                $link = '<ul class="contextMenu">'
                        + '<li class="edit"><a href="#download" data-role="'+data+'">Download</li>'
                        + '<li class="separator"><a href="#saveImg" data-role="'+data+'">Save Image</a></li>'
                        + '</ul>';
            	$($link)
            	.appendTo("body")
            	.css({display:'block', top: ev.pageY + "px", left: ev.pageX + "px"});
            }
        });
    }
    var callJqPlotlineChat = function(which, dTitle, data, minDate, seriesLabel) {
        var plot3 = $.jqplot(which
                , data
                , {
                    title: dTitle
                    , stackSeries: true
                    , showMarker: false
                    , seriesDefaults: {
                        fill: true
                    }
                    //, seriesColors: [ "#090", "#FFA500", "#F00"]
                    , axesDefaults: {
                        tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                        tickOptions: {
                            angle: -30
                        }
                    }
                    , axes: {
                        yaxis: {min:0}
                        , xaxis: {
                            renderer: $.jqplot.DateAxisRenderer,
                            tickOptions: {
                                formatString: '%b %#d, %#I %p'
                                , showGridline: false
                            },
                            // min: prev_friday(minDate[0]),
                            min: minDate[0]
                            , tickInterval: minDate[1]
                            , pad: 0
                            , padMin : minDate[0]
                        }
                    }
                    //, series: [{lineWidth: 4, markerOptions: {style: 'square'}}]
                    , legend: {show: true, location: 'e'}
                    , series: seriesLabel
                });
    }