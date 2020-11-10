
/*
|--------------------------------------------------------------------------
| GETTING A SINGLE ADMIN TO BE EDITED AND IT'S RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_dashboard_info_success_response_function(response)
{
    if(response.points_to_one_cedi.trim() != ""){
            $('#loader2').fadeOut();
            $('#loader3').fadeOut();
            $('#loader4').fadeOut();
            $('#loader7').fadeOut();
            $("#balance_now").html("Gh¢" + response.merchant_balance);
            $("#pending_redemptions").html(response.unpaid);
            $("#points_rate").html(response.points_to_one_cedi);
            $('#real_vcode_img').attr('src', response.merchant_vcode);
            $('#pending_redemptions').fadeIn();
            $('#points_rate_holder').fadeIn();
            $('#balance_now').fadeIn();
            $('#vcode_img').fadeIn();

            if(response.redemptions.length > 0){
                for (let index = 0; index < response.redemptions.length; index++) {
                    const element = response.redemptions[index];
                    url = '';
                    if(element.vendor_paid_fiat == 1){
                        var status = '<span class="badge badge-success">Paid</span>';
                        var action = '';
                    } else if(element.vendor_paid_fiat == 0){
                        var status = '<span class="badge badge-info">Pending</span>';
                        var action = '<img id="pay_icon" style="height:30px; width:30px;" src="/img/tick.png" />   <img id="decline_icon" style="height:30px; width:30px;" src="/img/wrong.png" />'
                    } else {
                        var status = '<span class="badge badge-danger">Declined</span>';
                        var action = '';
                    }
                    
                    $('#table_body_list').append(
                        '<tr style="cursor: ;" class="claim" data-url="' + url + '">'
                        + '<td>' + element.redemption_id + '</td>'
                        + '<td>' + element.customer_phone + '</td>'
                        + '<td>' + element.points_to_one_cedi_rate_used + '</td>'
                        + '<td>Gh¢' + element.redemption_cedi_equivalent_paid + '</td>'
                        + '<td>' + element.redeemed_points + '</td>'
                        + '<td>' + status +' </td>'
                        + '<td>' + element.created_at + '</td>'
                        + '<td>' + action + '</td>'
                        + '</tr>'
                    );
                }
                
        } else {
            show_notification("msg_holder", "danger", "", "Stats failed to load");
        }
    }
}

function get_dashboard_info_error_response_function(errorThrown)
{
    $('#loader').fadeOut();
    show_notification("msg_holder", "danger", "Error", errorThrown);
}


/*
|--------------------------------------------------------------------------
| FETCHING A SINGLE ADMIN FUNCTION
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/

function get_dashboard_info()
{
    //fade_in_loader_and_fade_out_form("loader", "stats_info");   
    var bearer = "Bearer " + localStorage.getItem("access_token"); 
    send_restapi_request_to_server_from_form("get", api_get_dashboard_stats_url, bearer, "", "json", get_dashboard_info_success_response_function, get_dashboard_info_error_response_function);
}
