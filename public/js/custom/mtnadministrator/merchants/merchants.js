$(document).ready(function () 
{
    // SUBMITTING 'ADD MERCHANT' FORM
    $("#form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "form");       
        var form_data = $("#form").serialize();
        var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
        send_restapi_request_to_server_from_form("post", api_add_merchant_url, bearer, form_data, "json", add_merchant_success_response_function, add_merchant_error_response_function);
    });

    // SUBMITTING 'SEARCH MERCHANT' FORM
    $("#search_form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "search_form");       
        var form_data = $("#search_form").serialize();
        var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
        var url = api_get_merchant_url + "?"+ form_data;
        show_log_in_console("url: " + url);
        send_restapi_request_to_server_from_form("get", api_get_merchant_url, bearer, form_data, "json", search_merchant_success_response_function, search_merchant_error_response_function);
    });
    
    /*
    |--------------------------------------------------------------------------
    | WHEN A MERCHANT IS CLICKED, WE SEND THE USER TO THE EDIT PAGE
    |--------------------------------------------------------------------------
    | FOR SOME REASON, I COULD NOT PUT AN <A> TAG DIRECTLY IN THE TABLE
    |--------------------------------------------------------------------------
    |
    */
   $(document).on('click', '.merchants', function () {
        show_log_in_console("url: " + (this).getAttribute("data-url"));
        redirect_to_next_page((this).getAttribute("data-url"), true);
    });
    
});

    // RESENDING THE PASSCODE
    function add_merchant_success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Merchant added successfully");
        fade_out_loader_and_fade_in_form("loader", "form"); 
        $('#form')[0].reset();
    }

    function add_merchant_error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "form"); 
        show_notification("msg_holder", "danger", "Error", errorThrown);
    }


/*
|--------------------------------------------------------------------------
| GETTING THE A SINGLE BUREAU TO BE EDITED AND IT'S RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function search_merchant_success_response_function(response)
{
    show_log_in_console("response: ");
    show_log_in_console(response);
    if(response.data.length > 0){
        const element = response.data[0];
        if(element.merchant_flagged == 0){
            $("#basic-tab").html("Active");
            $("#basic-tab").css("background", "green");
        } else { 
            $("#basic-tab").html("Flagged");
            $("#basic-tab").css("background", "red");
        }
        $("#merchant_name2").html(element.merchant_name);
        $("#merchant_name").html(element.merchant_name);
        $("#merchant_location").html(element.merchant_location);
        $("#merchant_phone_number2").html(element.merchant_phone_number);
        $("#merchant_email").html(element.merchant_email);
        $("#pts_to_1_cedis_hvc").html(element.pts_to_1_cedis_hvc);
        $("#pts_to_1_cedis_nc").html(element.pts_to_1_cedis_nc);
        $("#created_at").html(element.created_at);
        $("#merchant_vcode_img").attr('src', element.merchant_vcode_link);

        $("#merchant_pending_claims").html(element.merchant_unpaid_claims);
        $("#merchant_balance").html(element.merchant_balance);
        $("#admin_name").html(element.admin_fullname);
        
        $('#profile_merchant').fadeIn();
        $('#claims_table').fadeIn();


        if(response.redemptions.length > 0){
            $('#table_body_list').html("");
            for (let index = 0; index < response.redemptions.length; index++) {
                const element = response.redemptions[index];
                if(element.vendor_paid_fiat == 1){
                    var status = '<span class="badge badge-success">Redeemed</span>';
                } else if(element.vendor_paid_fiat == 0){
                    var status = '<span class="badge badge-info">Pending</span>';
                } else {
                    var status = '<span class="badge badge-danger">Declined</span>';
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
                    + '</tr>'
                );
            }
            
        } else {
            show_notification("msg_holder", "danger", "", "No redemptions found for this merchant");
        }

        fade_out_loader_and_fade_in_form("loader", "search_form"); 
    } else {
        fade_out_loader_and_fade_in_form("loader", "search_form"); 
        show_notification("msg_holder", "danger", "", "Merchant not found");
    }
}

function search_merchant_error_response_function(errorThrown)
{
    fade_out_loader_and_fade_in_form("loader", "search_form"); 
    show_notification("msg_holder", "danger", "Error", errorThrown);
}


/*
|--------------------------------------------------------------------------
| FETCHING A SINGLE BUEAU FUNCTION
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/

function get_this_bureau(bureau_id)
{
    fade_in_loader_and_fade_out_form("loader", "edit_bureau_form");   
    var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
    url = admin_api_bureaus_get_one_bureau_url + bureau_id;
    show_log_in_console("url: " + url);
    send_restapi_request_to_server_from_form("get", url, bearer, "", "json", get_this_bureau_success_response_function, get_this_bureau_error_response_function);
}




