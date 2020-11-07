
$(document).ready(function () 
{
    $("#form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "form");       
        var form_data = $("#form").serialize();
        var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
        send_restapi_request_to_server_from_form("post", api_add_admin_url, bearer, form_data, "json", add_admin_success_response_function, add_admin_error_response_function);
    });

    // UPDATING ADMIN FUNCTION
    $("#updateform").submit(function (e) 
    { 
        e.preventDefault(); 
            var form_data_json = get_json_from_form_data(e);
            fade_in_loader_and_fade_out_form("loader", "updateform");       
            var bearer = "Bearer " + localStorage.getItem("access_token"); 
            var url = api_update_one_admin_url + i;
            show_log_in_console("Bearer: " + bearer);
            show_log_in_console("url: " + url);
            show_log_in_console("form_date: " + $("#form").serialize());
            show_log_in_console(form_data_json);
            send_restapi_request_to_server_from_form("POST", url, bearer, form_data_json, "", update_success_response_function, update_error_response_function);

    });

    
    /*
    |--------------------------------------------------------------------------
    | WHEN AN ADMIN IS CLICKED, WE SEND THE USER TO THE EDIT PAGE
    |--------------------------------------------------------------------------
    | FOR SOME REASON, I COULD NOT PUT AN <A> TAG DIRECTLY IN THE TABLE
    |--------------------------------------------------------------------------
    |
    $(document).on('click', '.administrator', function () {
        show_log_in_console("url: " + (this).getAttribute("data-url"));
        redirect_to_next_page((this).getAttribute("data-url"), true);
    });
    */

});


    // UPDATING ADMIN SUCCESS RESPONSE FUNCTION
    function update_success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Admin updated successfully");
        fade_out_loader_and_fade_in_form("loader", "updateform"); 
    }

    // UPDATING ADMIN ERROR RESPONSE FUNCTION
    function update_error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "updateform"); 
        show_notification("msg_holder", "danger", "Error", errorThrown);
    }




    // ADDING ADMIN SUCCESS RESPONSE FUNCTION
    function add_admin_success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Admin added successfully");
        fade_out_loader_and_fade_in_form("loader", "form"); 
        $('#form')[0].reset();
    }

    // ADDING ADMIN ERROR RESPONSE FUNCTION
    function add_admin_error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "form"); 
        show_notification("msg_holder", "danger", "Error", errorThrown);
    }


/*
|--------------------------------------------------------------------------
| GETTING THE LIST OF ADMINS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_admins_for_page_success_response_function(response)
{
    fade_out_loader_and_fade_in_form("loader", "dataTableExample"); 
    if(response.data.length > 0){
        for (let index = 0; index < response.data.length; index++) {
            const element = response.data[index];
            url = hostweb + "/admin/administrators/edit/" + element.admin_id;
            if(element.admin_flagged == 0){
                var status = '<span class="u-label bg-success text-white">Active</span>';
            } else {
                var status = '<span class="u-label bg-warning text-white">Inactive</span>';
            }
            $('#table_body_list').append(
                '<tr style="cursor: ;" class="administrator" data-url="' + url + '">'
                + '<td>' + element.admin_id + '</td>'
                + '<td>' + element.admin_surname + ' ' + element.admin_firstname + '</td>'
                + '<td>' + element.admin_phone_number + '</td>'
                + '<td>' + element.admin_email + '</td>'
                + '<td>' + status +' </td>'
                + '<td>' + element.creator_name + '</td>'
                + '<td>' + element.created_at + '</td>'
                + '</tr>'
            );
        }
        //document.getElementById("next_btn").style.display = "";
    } else {
        show_notification("msg_holder", "danger", "", "No admins found");
    }
}

function get_admins_for_page_error_response_function(errorThrown)
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
function get_admins_for_page(page_number)
{
    fade_in_loader_and_fade_out_form("loader", "dataTableExample");   
    var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
    send_restapi_request_to_server_from_form("get", api_list_admins_url, bearer, "", "json", get_admins_for_page_success_response_function, get_admins_for_page_error_response_function);
}



/*
|--------------------------------------------------------------------------
| GETTING A SINGLE ADMIN TO BE EDITED AND IT'S RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
*/
function get_this_admin_success_response_function(response)
{
    if(response.email.trim() != ""){
            const element = response;
            $("#username").val(element.username);
            $('#submit_button_holder').html(
               '<button type="submit" class="btn btn-primary mr-2">Submit</button>'
            );
            fade_out_loader_and_fade_in_form("loader", "updateform"); 
    } else {
        $('#loader').fadeOut();
        show_notification("msg_holder", "danger", "", "Admin not found");
    }
}

function get_this_admin_error_response_function(errorThrown)
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

function get_this_admin(admin_id)
{
    fade_in_loader_and_fade_out_form("loader", "updateform");   
    var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
    url = api_get_one_admin_url + admin_id;
    show_log_in_console("url: " + url);
    send_restapi_request_to_server_from_form("get", url, bearer, "", "json", get_this_admin_success_response_function, get_this_admin_error_response_function);
}


