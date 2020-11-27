
$(document).ready(function () 
{    
    /*
    |--------------------------------------------------------------------------
    | WHEN AN ADMIN IS CLICKED, WE SEND THE USER TO THE EDIT PAGE
    |--------------------------------------------------------------------------
    | FOR SOME REASON, I COULD NOT PUT AN <A> TAG DIRECTLY IN THE TABLE
    |--------------------------------------------------------------------------
    |
    */
    $(document).on('click', '.pay-icon', function () {

        show_notification("msg_holder", "success", "", "Vendor has been credited the amount via MoMo on their mobile number");
    //pay_url = host_api + '/api/v1/merchant/redemptions/update?=redemption_id=' + element.redemption_id;
    //redirect_to_next_page((this).getAttribute("data-url"), true);
});
$(document).on('click', '.decline-icon', function () {

        show_notification("msg_holder", "success", "", "Vendor has been alerted of the decline via SMS");
    //pay_url = host_api + '/api/v1/merchant/redemptions/update?=redemption_id=' + element.redemption_id;
    //redirect_to_next_page((this).getAttribute("data-url"), true);
});

});

/*
|--------------------------------------------------------------------------
| GETTING A SINGLE ADMIN TO BE EDITED AND IT'S RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_dashboard_info_success_response_function(response)
{
    console.log(response);
    if(response.pts_to_1_cedis_nc.trim() != ""){
            $('#hvc_rate_loader').fadeOut();
            $('#nc_rate_loader').fadeOut();
            $('#vendors_loader').fadeOut();
            $("#hvc_rate").html(response.pts_to_1_cedis_hvc);
            $("#nc_rate").html(response.pts_to_1_cedis_nc);
            $("#vendors").html(response.merchants_count);
            $('#hvc_rate_span').fadeIn();
            $('#nc_rate_span').fadeIn();
            $('#vendors').fadeIn();

            if(response.merchants.length > 0){
                for (let index = 0; index < response.merchants.length; index++) {
                    const element = response.merchants[index];
                    url = '';
                    if(element.merchant_flagged == 1){
                        var status = '<span class="badge badge-danger">Flagged</span>';
                    } else if(element.merchant_flagged == 0){
                        var status = '<span class="badge badge-success">Active</span>';
                    } else {
                        var status = '<span class="badge badge-info">Unknown</span>';
                    }
                    $('#table_body_list').append(
                        '<tr style="cursor: ;" class="claim" data-url="' + url + '">'
                        + '<td>' + element.merchant_id + '</td>'
                        + '<td>' + element.merchant_name + '</td>'
                        + '<td>' + element.merchant_phone_number + '</td>'
                        + '<td>' + element.pts_to_1_cedis_hvc + '</td>'
                        + '<td>' + element.pts_to_1_cedis_nc + '</td>'
                        + '<td>' + status +' </td>'
                        + '<td>' + element.admin_fullname + '</td>'
                        + '<td>' + element.created_at + '</td>'
                        + '</tr>'
                    );
                }
                
        } else {
            show_notification("msg_holder", "danger", "", "No vendors found");
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
    var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
    send_restapi_request_to_server_from_form("get", api_get_dashboard_stats_url, bearer, "", "json", get_dashboard_info_success_response_function, get_dashboard_info_error_response_function);
}

function get_claims_success_response_function(response)
{
    fade_out_loader_and_fade_in_form("loader", "dataTableExample"); 
    if(response.data.length > 0){
        for (let index = 0; index < response.data.length; index++) {
            const element = response.data[index];
            url = hostweb + "/admin/administrators/edit/" + element.admin_id;
            if(element.admin_flagged == 0){
                var status = '<span class="badge badge-success">Active</span>';
            } else if(element.admin_flagged == 0){
                var status = '<span class="badge badge-info">Active</span>';
            } else {
                var status = '<span class="badge badge-danger">Inactive</span>';
            }
            $('#table_body_list').append(
                '<tr style="cursor: ;" class="claim" data-url="' + url + '">'
                + '<td>' + element.admin_id + '</td>'
                + '<td>' + element.admin_surname + ' ' + element.admin_firstname + '</td>'
                + '<td>' + element.admin_phone_number + '</td>'
                + '<td>' + element.admin_email + '</td>'
                + '<td>' + element.creator_name + '</td>'
                + '<td>' + status +' </td>'
                + '<td>' + element.created_at + '</td>'
                + '</tr>'
            );
        }
        //document.getElementById("next_btn").style.display = "";
    } else {
        $('#loader').fadeOut();
    }
}

function get_claims_error_response_function(errorThrown)
{
    show_notification("msg_holder", "danger", "Error", errorThrown);
}


/*
|--------------------------------------------------------------------------
| GETTING THE LIST OF ADMINS FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_claims(page_number)
{
    fade_in_loader_and_fade_out_form("loader", "dataTableExample");   
    var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
    send_restapi_request_to_server_from_form("get", api_list_admins_url, bearer, "", "json", get_claims_success_response_function, get_claims_error_response_function);
}

