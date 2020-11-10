/****************************************
    
                WEB PAGE URLS

****************************************/

var hostweb = "http://mtncustomerloyalty";
var show_logging_in_console = "true"

// LOGIN PAGE URL
var web_login_url = `${hostweb}/login`;

// INDEX PAGE URL
var web_home_url = `${hostweb}/dashboard`;

/****************************************
    
                API URLS

****************************************/

var host_api = "http://mtncustomerloyalty";

// LOGIN PAGE URLS
var api_merchant_login_url = `${host_api}/api/v1/merchant/login`;
//var api_user_info_url = `${host_api}/me`;



// LIST ADMINS
var api_list_admins_url = `${host_api}/api/v1/admin/administrators/list`;

// GET ONE ADMIN INFO
var api_get_dashboard_stats_url = `${host_api}/api/v1/merchant/dashboard/get`;

var api_get_claims_url = `${host_api}/api/v1/merchant/claims/list`;

var api_add_claim_url = `${host_api}/api/v1/merchant/claims/add`;

// UPDATE ONE ADMIN INFO
var api_get_redemptions = `${host_api}/api/v1/merchant/redemptions/search`;

// UPDATE ONE ADMIN INFO
var api_update_redemption = `${host_api}/api/v1/merchant/redemptions/update`;


// CHANGE PASSWORD
var api_change_password_url = `${host_api}/v1/password/`;




// LOGGING INFORMATION
function show_log_in_console(log){
    if(show_logging_in_console){
        console.log(log);
    }
}


// CHECKING IF USER HAS AN API TOKEN
function user_has_api_token()
{
    if(
        (localStorage.getItem("access_token") != null && localStorage.getItem("access_token").trim() != "")
         && (localStorage.getItem("merchant_name") != null && localStorage.getItem("merchant_name").trim() != "")
    
    )
    {
        return true;
    } else {
        return false;
    }
}


// LOGGING USER OUT BY DELETING ACCESS TOKEN
function delete_user_authentication()
{
    localStorage.clear();
    show_log_in_console("user_deleted");
}

function user_token_is_no_longer_valid()
{
    delete_user_authentication();
    redirect_to_next_page(web_login_url, false); 
}

function sign_out_success(response)
{
    delete_user_authentication(); 
    user_token_is_no_longer_valid()
}

function sign_out_error(errorThrown)
{
    show_notification("msg_holder", "danger", "Error", errorThrown);
    fade_out_loader_and_fade_in_form("logoutloader", "logoutspan");   
}

function sign_me_out()
{    
    fade_in_loader_and_fade_out_form("logoutloader", "logoutspan");     
    var bearer = "Bearer " + localStorage.getItem("access_token"); 
    send_restapi_request_to_server_from_form("get", api_logout_url, bearer, "", "json", sign_out_success, sign_out_error);
}

function hide_notification(){
    document.getElementById('msg_div').style.display = "none";
}

// SHOWING A NOTIFICATION ON THE SCREEN
function show_notification(id, type, title, message)
{
    $('#'+id).html(
        '<div id="msg_div" class="' + type + '"><b>' + title +' </b> '+ message +'<a id="close-bar" onclick="hide_notification();">×</a></div>'
    );

    setTimeout(function(){ $('#close-bar').click(); }, 5000);
}


// SHOWING A LOADER AND DISAPPEARING FORM
function fade_in_loader_and_fade_out_form(loader_id, form_id)
{
    $('#'+form_id).fadeOut();
    $('#'+loader_id).fadeIn();
}

// SHOWING A FORM AND DISAPPEARING LOADER
function fade_out_loader_and_fade_in_form(loader_id, form_id)
{
    $('#'+loader_id).fadeOut();
    $('#'+form_id).fadeIn();
}

// SENDING USER TO NEW PAGE
function redirect_to_next_page(url, can_return_to_page)
{
    if(can_return_to_page){// Simulate a mouse click:
        setTimeout(window.location.href = url, 7000);
    } else {
        setTimeout(window.location.replace(url), 7000);
    }
}

function get_json_from_form_data(e)
{
    const formData = new FormData(e.target);
    var object = {};
    formData.forEach(function(value, key){
        object[key] = value;
    });
    return JSON.stringify(object);

}

function send_request_to_server_from_form(method, url_to_server, form_data, data_type, success_response_function, error_response_function)
{
    $.ajax({
        type: method,
        url: url_to_server,
        data:  form_data,
        dataType: data_type,
        success: function(response){ 
            show_log_in_console(response);
            if(response.status.trim() == "success"){
                success_response_function(response);
            } else {
                error_response_function(response.message);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            show_log_in_console(errorThrown);
            error_response_function(errorThrown);
        }
    });
}

function send_restapi_request_to_server_from_form(method, url_to_server, authorization, form_data, data_type, success_response_function, error_response_function)
{
    $.ajax({
        type: method,
        url: url_to_server,headers: {
            'Authorization': authorization
         },
        data:  form_data,
        dataType: data_type,
        success: function(response){ 
            show_log_in_console(response);

            if(response == "Unauthorized"){
                user_token_is_no_longer_valid();
                return;
            } 
            if(response.status.trim() == "success"){
                success_response_function(response);
            } else {
                error_response_function(response.message);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            show_log_in_console(errorThrown);
            if(errorThrown == "Unauthorized"){
                user_token_is_no_longer_valid();
                return;
            }
            error_response_function(errorThrown);
        }
    });
}