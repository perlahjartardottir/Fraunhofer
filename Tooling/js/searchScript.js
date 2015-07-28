function suggestions(){
    $('#output').html();
    $('#output').html('<center style="margin-top:200px;"><img src="../images/ajax-loader.gif"></center>');
    var po_number       = $('#search_box_PO').val();
    var customer_select = document.getElementById("customer_select");
    var customer_ID     = customer_select.options[customer_select.selectedIndex].value;
    var order_by_select = document.getElementById("order_by_select");
    var order_by        = order_by_select.options[order_by_select.selectedIndex].value;
    var first_date      = $('#search_box_date_first').val();
    var last_date       = $('#search_box_date_last').val();
    var top_100;

    if($('#top_100').is(':checked')){
        top_100   = $('#top_100').val();
    }
    $.ajax({
        url : "../SearchPHP/search_suggestions.php",
        type: "POST",
        data : {po_number   : po_number,
                customer_ID : customer_ID,
                order_by    : order_by,
                first_date  : first_date,
                last_date   : last_date,
                top_100     : top_100},
     success: function(data,status, xhr)
     {
        $( "#output" ).html(data);
     }
    });
}
function setSessionIDSearch(po_ID){
    $.ajax({
        url : "../UpdatePHP/setSessionID.php",
        type: "POST",
        data : {po_ID : po_ID},
     success: function(data,status, xhr)
     {
     }
    });
}
function run_suggestions(){
    $('#output').html();
    $('#output').html('<center style="margin-top:200px;"><img src="../images/ajax-loader.gif"></center>');
    var run_number      = $('#search_box_run').val();
    var machine_sel     = document.getElementById("machine_select");
    var machine_ID      = machine_sel.options[machine_sel.selectedIndex].value;
    var coating_sel     = document.getElementById("coating_select");
    var coating_ID      = coating_sel.options[coating_sel.selectedIndex].value;
    var first_date      = $('#search_box_date_first').val();
    var last_date       = $('#search_box_date_last').val();
    var ah_pulses       = $('#search_box_ah').val();
    var order_by_select = document.getElementById("order_by_select");
    var order_by        = order_by_select.options[order_by_select.selectedIndex].value;
    var top_runs;

    if($('#top_runs').is(':checked')){
        top_runs   = $('#top_runs').val();
    }
    //var exact_date = $('#search_box_date_last').val();
    $.ajax({
        url : "../SearchPHP/run_search_suggestions.php",
        type: "POST",
        data : {run_number : run_number,
                machine_ID : machine_ID,
                coating_ID : coating_ID,
                ah_pulses  : ah_pulses,
                top_runs     : top_runs,
                first_date : first_date,
                order_by    : order_by,
                last_date  : last_date},
     success: function(data,status, xhr){
        $( "#output" ).html(data);
     }
    });
}

function tool_suggestions(){
    $('#output').html();
    $('#output').html('<center style="margin-top:200px;"><img src="../images/ajax-loader.gif"></center>');
    var tool_ID         = $('#tool_ID').val();
    var first_date      = $('#search_box_date_first').val();
    var last_date       = $('#search_box_date_last').val();
    var order_by_select = document.getElementById("order_by_select");
    var order_by        = order_by_select.options[order_by_select.selectedIndex].value;
    var top_runs;

    if($('#top_runs').is(':checked')){
        top_runs   = $('#top_runs').val();
    }
    $.ajax({
        url: "../SearchPHP/tool_search_suggestions.php",
        type: "POST",
        data: {
            tool_ID   : tool_ID,
            top_runs  : top_runs,
            first_date: first_date,
            order_by  : order_by,
            last_date : last_date},
        success: function(data, status, xhr){
            $("#output").html(data);
        }
    });
}
function oldPOsTable(){
    $.ajax({
        url: "../SearchPHP/oldPOsTable.php",
        type: "POST",
        data: {},
        success: function(data, status, xhr){
            $("#output").html(data);
        }
    });
}

function generalInfoRedirect(po_ID){
    $.ajax({
        url : "../UpdatePHP/setSessionID.php",
        type: "POST",
        data : {po_ID : po_ID},
     success: function(data,status, xhr)
     {
         var url = "../Printouts/generalinfo.php";
         window.open(url, '_blank');
     }
    });
}
function trackSheetRedirect(po_ID){
    $.ajax({
        url : "../UpdatePHP/setSessionID.php",
        type: "POST",
        data : {po_ID : po_ID},
     success: function(data,status, xhr)
     {
         var url = "../Printouts/tracksheet.php";
         window.open(url, '_blank');
     }
    });
}
function editTrackSheetRedirect(po_ID){
    $.ajax({
        url : "../UpdatePHP/setSessionID.php",
        type: "POST",
        data : {po_ID : po_ID},
     success: function(data,status, xhr)
     {
         var url = "../Views/generateTrackSheet.php";
         window.open(url);
     }
    });
}
