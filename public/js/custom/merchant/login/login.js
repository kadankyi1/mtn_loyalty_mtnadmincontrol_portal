$(document).ready(function () 
{
    // RESENDING THE PASSCODE
    function success_response_function(response)
    {
        localStorage.setItem("access_token", response.token);
        localStorage.setItem("refresh_token", response.refresh_token);

        get_user_account_info();
    }

    function error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "form"); 
        show_notification("msg_holder", "danger", "Error:", errorThrown);
    }

    // SUBMITTING THE LOGIN FORM TO GET API TOKEN
    $("#form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "form");       
        send_request_to_server_from_form("post", api_login_url, $("#form").serialize(), "json", success_response_function, error_response_function);
    });

    
});

// GETTING USER ACCOUNT INFO
function get_user_info_success_response_function(response)
{
    show_log_in_console(response);
    localStorage.setItem("active", response.active);
    localStorage.setItem("address", response.address);
    localStorage.setItem("created_at", response.created_at);
    localStorage.setItem("deleted_at", response.deleted_at);
    localStorage.setItem("email", response.email);
    localStorage.setItem("first_name", response.first_name);
    localStorage.setItem("id", response.id);
    localStorage.setItem("last_login", response.last_login);
    localStorage.setItem("last_name", response.last_name);
    localStorage.setItem("last_password_change", response.last_password_change);
    localStorage.setItem("mobile", response.mobile);
    localStorage.setItem("phone", response.phone);
    localStorage.setItem("updated_at", response.updated_at);
    localStorage.setItem("username", response.username);

    show_notification("msg_holder", "success", "Success:", "Login successful");
    redirect_to_next_page(web_home_url, false);
}

function get_user_info_error_response_function(errorThrown)
{
    show_log_in_console("HERE 2");
    fade_out_loader_and_fade_in_form("loader", "form"); 
    show_notification("msg_holder", "danger", "Error:", errorThrown);
}


function get_user_account_info()
{  
    var bearer = "Bearer " + localStorage.getItem("access_token"); 
    send_restapi_request_to_server_from_form("get", api_user_info_url, bearer, "", "json", get_user_info_success_response_function, get_user_info_error_response_function);
}