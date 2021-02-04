
$(document).ready(function () 
{
    $("#search_form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "search_form");  
        fade_in_loader_and_fade_out_form("loader2", "claims_table");   
        var form_data = $("#search_form").serialize();
        var url = api_get_redemptions + "?=" + form_data;
        var bearer = "Bearer " + localStorage.getItem("access_token"); 
        show_log_in_console("url: " + url);
        send_restapi_request_to_server_from_form("get", url, bearer, form_data, "json", get_redemptions_success_response_function, get_redemptions_error_response_function);
    });

    
    /*
    |--------------------------------------------------------------------------
    | WHEN AN ADMIN IS CLICKED, WE SEND THE USER TO THE EDIT PAGE
    |--------------------------------------------------------------------------
    | FOR SOME REASON, I COULD NOT PUT AN <A> TAG DIRECTLY IN THE TABLE
    |--------------------------------------------------------------------------
    |
    */
    $(document).on('click', '.pay-icon', function () {
        show_log_in_console("redemption_id: " + (this).getAttribute("data-rid"));
        show_log_in_console("redemption_type: " + (this).getAttribute("data-rtype"));

        $("#pay_icon_" + (this).getAttribute("data-rid")).fadeOut();
        $("#decline_icon_" + (this).getAttribute("data-rid")).fadeOut();
        $("#loader_for_redemption_id_" + (this).getAttribute("data-rid")).fadeIn();

        var form_data =  "redemption_id=" + (this).getAttribute("data-rid") + "&vendor_paid_fiat=" + (this).getAttribute("data-rtype");
        var url = api_update_redemption + "?=" + form_data;
        var bearer = "Bearer " + localStorage.getItem("access_token"); 
        show_log_in_console("url: " + url);
        send_restapi_request_to_server_from_form("get", api_update_redemption, bearer, form_data, "json", update_redemption_success_response_function, update_redemption_error_response_function);
    
        //pay_url = host_api + '/api/v1/merchant/redemptions/update?=redemption_id=' + element.redemption_id;
        //redirect_to_next_page((this).getAttribute("data-url"), true);
    });

});


function update_redemption_success_response_function(response)
{
    $("#pay_icon_" + response.redemption_id).remove();
    $("#decline_icon_" + response.redemption_id).remove();
    $("#loader_for_redemption_id_" + response.redemption_id).remove();
    if(response.vendor_paid_fiat == 1){
        var status = '<span class="badge badge-success">Redeemed</span>';
    } else if(response.vendor_paid_fiat == 2){
        var status = '<span class="badge badge-danger">Declined</span>';
    }
    $("#status_for_" + response.redemption_id).html(status);
    show_notification("msg_holder", "success", "", response.message);
}

function update_redemption_error_response_function(errorThrown)
{
    $('#loader2').fadeOut();
    show_notification("msg_holder", "danger", "Error", errorThrown);
}

/*
|--------------------------------------------------------------------------
| GETTING A SINGLE ADMIN TO BE EDITED AND IT'S RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_redemptions_success_response_function(response)
{
    if(response.redemptions.length > 0){
        $('#table_body_list').html("");
        for (let index = 0; index < response.redemptions.length; index++) {
            const element = response.redemptions[index];
            if(element.vendor_paid_fiat == 1){
                var status = '<span class="badge badge-success">Redeemed</span>';
                var action = '';
            } else if(element.vendor_paid_fiat == 0){
                var status = '<span class="badge badge-info">Pending</span>';
                var action = '<div class="d-flex justify-content-center"><div id="loader_for_redemption_id_'+ element.redemption_id + '" style="display: none;" class="customloader"></div></div> <img class="pay-icon" data-rid="' + element.redemption_id + '" data-rtype="1" id="pay_icon_'+ element.redemption_id + '" style="height:30px; width:30px; cursor: pointer;" src="/img/tick.png" />   <img  class="pay-icon" id="decline_icon_'+ element.redemption_id + '"  data-rid="' + element.redemption_id + '" data-rtype="2" style="height:30px; width:30px; cursor: pointer;" src="/img/wrong.png" />'
            } else {
                var status = '<span class="badge badge-danger">Declined</span>';
                var action = '';
            }
            $('#table_body_list').append(
                '<tr style="cursor: ;" class="claim">'
                + '<td>' + element.redemption_id + '</td>'
                + '<td>' + element.customer_phone + '</td>'
                + '<td>' + element.points_to_one_cedi_rate_used + '</td>'
                + '<td>Gh¢' + element.redemption_cedi_equivalent_paid + '</td>'
                + '<td>' + element.redeemed_points + '</td>'
                + '<td id="status_for_' + element.redemption_id  + '">' + status +' </td>'
                + '<td>' + element.created_at + '</td>'
                + '<td>' + action + '</td>'
                + '</tr>'
            );
        }
        
        $('#search_form').fadeIn();
        $('#loader').fadeOut();
        $('#loader2').fadeOut();
        $('#claims_table').fadeIn();
    } else {
        $('#search_form').fadeIn();
        $('#loader').fadeOut();
        $('#loader2').fadeOut();
        $('#claims_table').fadeIn();
        show_notification("msg_holder", "danger", "", "No redemptions found");
    }
}

function get_redemptions_error_response_function(errorThrown)
{
    $('#loader2').fadeOut();
    show_notification("msg_holder", "danger", "Error", errorThrown);
}


/*
|--------------------------------------------------------------------------
| FETCHING A SINGLE ADMIN FUNCTION
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/

function get_redemptions()
{
    //fade_in_loader_and_fade_out_form("loader", "stats_info");   
    var bearer = "Bearer " + localStorage.getItem("access_token"); 
    send_restapi_request_to_server_from_form("get", api_get_redemptions, bearer, "", "json", get_redemptions_success_response_function, get_redemptions_error_response_function);
}
