$(document).ready(function () 
{
    // RESENDING THE PASSCODE
    function success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Password changed successfully");
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
        if(document.getElementById("n_pass").value == document.getElementById("confirm_n_pass").value){

            var form_data_json = get_json_from_form_data(e);
            fade_in_loader_and_fade_out_form("loader", "form");       
            var bearer = "Bearer " + localStorage.getItem("access_token"); 
            var url = api_change_password_url +  localStorage.getItem("id");


            show_log_in_console("bearer: " + bearer);
            show_log_in_console("url: " + url);
            show_log_in_console("form_data: " + $("#form").serialize());
            show_log_in_console(form_data_json);


            send_restapi_request_to_server_from_form("patch", url, bearer, form_data_json, "json", success_response_function, error_response_function);
        } else {
            show_notification("msg_holder", "danger", "", "Passwords do not match");
        }
    });

    
});

