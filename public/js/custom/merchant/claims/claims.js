
$(document).ready(function () 
{
    $("#form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "form");       
        var form_data = $("#form").serialize();
        var bearer = "Bearer " + localStorage.getItem("access_token"); 
        
        send_restapi_request_to_server_from_form("post", api_add_claim_url, bearer, form_data, "json", add_claim_success_response_function, add_claim_error_response_function);
    });
});



    // ADDING ADMIN SUCCESS RESPONSE FUNCTION
    function add_claim_success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Claim made successfully");
        fade_out_loader_and_fade_in_form("loader", "form"); 
        $("#balance_now").html("Gh¢" + response.new_balance);
        $('#form')[0].reset();
    }

    // ADDING ADMIN ERROR RESPONSE FUNCTION
    function add_claim_error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "form"); 
        show_notification("msg_holder", "danger", "Error", errorThrown);
    }

/*
|--------------------------------------------------------------------------
| GETTING A SINGLE ADMIN TO BE EDITED AND IT'S RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_claims_info_success_response_function(response)
{
    if(response.merchant_balance.trim() != ""){
            $('#loader2').fadeOut();
            $('#loader3').fadeOut();
            $("#balance_now").html("Gh¢" + response.merchant_balance);
            $("#pending_redemptions").html(response.unpaid);
            $('#pending_redemptions').fadeIn();
            $('#balance_now').fadeIn();

            if(response.claims.length > 0){
                for (let index = 0; index < response.claims.length; index++) {
                    const element = response.claims[index];
                    url = '';
                    if(element.paid_status == 1){
                        var status = '<span class="badge badge-success">Paid</span>';
                        var action = '';
                    } else if(element.paid_status == 0){
                        var status = '<span class="badge badge-info">Pending</span>';
                    } else {
                        var status = '<span class="badge badge-danger">Declined</span>';
                        var action = '';
                    }
                    $('#table_body_list').append(
                        '<tr style="cursor: ;" class="claim" data-url="' + url + '">'
                        + '<td>' + element.claim_id + '</td>'
                        + '<td>Gh¢' + element.claim_amount + '</td>'
                        + '<td>' + status +' </td>'
                        + '<td>' + element.admin_fullname + '</td>'
                        + '<td>' + element.created_at + '</td>'
                        + '</tr>'
                    );;
                }
                
        } else {
            show_notification("msg_holder", "danger", "", "Stats failed to load");
        }
    }
}

function get_claims_info_error_response_function(errorThrown)
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

function get_claims_info()
{
    //fade_in_loader_and_fade_out_form("loader", "stats_info");   
    var bearer = "Bearer " + localStorage.getItem("access_token"); 
    send_restapi_request_to_server_from_form("get", api_get_claims_url, bearer, "", "json", get_claims_info_success_response_function, get_claims_info_error_response_function);
}
