$(document).ready(function () 
{
    // RESENDING THE PASSCODE
    function success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Merchant added successfully");
        fade_out_loader_and_fade_in_form("loader", "form"); 
        $('#form')[0].reset();
    }

    function error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "form"); 
        show_notification("msg_holder", "danger", "Error", errorThrown);
    }

    // SUBMITTING THE LOGIN FORM TO GET API TOKEN
    $("#form").submit(function (e) 
    { 
        e.preventDefault(); 
        if(document.getElementById("pass").value == document.getElementById("pass_confirm").value){
            var form_data_json = get_json_from_form_data(e);
            form_data_json = JSON.parse(form_data_json);
            form_data_json.role_id = parseInt(form_data_json.role_id);
            show_log_in_console("role_id");
            show_log_in_console(form_data_json.role_id);
            form_data_json = JSON.stringify(form_data_json);

            fade_in_loader_and_fade_out_form("loader", "form");       
            var bearer = "Bearer " + localStorage.getItem("access_token"); 
            show_log_in_console("Bearer: " + bearer);
            show_log_in_console("url: " + api_create_user_url);
            show_log_in_console("form_date: " + $("#form").serialize());
            show_log_in_console(form_data_json);
            show_log_in_console("role_id");
            show_log_in_console(form_data_json.role_id);
            send_restapi_request_to_server_from_form("POST", api_create_user_url, bearer, form_data_json, "", success_response_function, error_response_function);
        } else {
            show_notification("msg_holder", "danger", "", "Passwords do not match");
        }
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


/*
|--------------------------------------------------------------------------
| GETTING THE LIST OF ADMINS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_merchants_for_page_success_response_function(response)
{
    fade_out_loader_and_fade_in_form("loader", "dataTableExample"); 
    if(response.merchants.length > 0){
        for (let index = 0; index < response.merchants.length; index++) {
            const element = response.merchants[index];
            url = hostweb + "/merchants/edit/" + element.id;
            if(element.active == true){
                var status = '<span class="u-label bg-success text-white">Active</span>';
            } else {
                var status = '<span class="u-label bg-warning text-white">Inactive</span>';
            }
            $('#table_body_list').append(
                '<tr style="cursor: pointer;" class="merchants" data-url="' + url + '">'
                + '<td>' + element.id + '</td>'
                + '<td>' + element.merchant.name + '</td>'
                + '<td>' + element.username + '</td>'
                + '<td>' + element.email + '</td>'
                + '<td>' + element.merchant.points + '</td>'
                + '<td>' + status +' </td>'
                + '<td>' + element.last_login + '</td>'
                /* + '<td>' + element.last_password_change + '</td>'  */
                + '<td>' + element.updated_at + '</td>'
                + '<td>' + element.created_at + '</td>'
                + '<td>' + element.deleted_at + '</td>'
                + '</tr>'
            );
        }
        //document.getElementById("next_btn").style.display = "";
    } else {
        show_notification("msg_holder", "danger", "", "No merchants found");
    }
}

function get_merchants_for_page_error_response_function(errorThrown)
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
function get_merchants_for_page(page_number)
{
    fade_in_loader_and_fade_out_form("loader", "dataTableExample");   
    var bearer = "Bearer " + localStorage.getItem("access_token"); 
    send_restapi_request_to_server_from_form("get", api_list_merchants_url, bearer, "", "json", get_merchants_for_page_success_response_function, get_merchants_for_page_error_response_function);
}
