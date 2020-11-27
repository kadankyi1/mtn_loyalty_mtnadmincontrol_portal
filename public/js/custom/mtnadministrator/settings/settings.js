$(document).ready(function () 
{
    // SUBMITTING 'ADD MERCHANT' FORM
    $("#form").submit(function (e) 
    { 
        e.preventDefault(); 
        fade_in_loader_and_fade_out_form("loader", "form");       
        var form_data = $("#form").serialize();
        var bearer = "Bearer " + localStorage.getItem("admin_access_token"); 
        send_restapi_request_to_server_from_form("post", api_update_rate, bearer, form_data, "json", add_rate_success_response_function, add_rate_error_response_function);
    });

    /*
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
    */
    
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
    function add_rate_success_response_function(response)
    {
        show_notification("msg_holder", "success", "Success:", "Operation successful");
        fade_out_loader_and_fade_in_form("loader", "form"); 
        $('#form')[0].reset();
    }

    function add_rate_error_response_function(errorThrown)
    {
        fade_out_loader_and_fade_in_form("loader", "form"); 
        show_notification("msg_holder", "danger", "Error", errorThrown);
    }


