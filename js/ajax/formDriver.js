function formDriver(){}

formDriver.DEFAULT_METHOD = "POST";
formDriver.URL_REQUEST = "./php/ajax/formDriver.php";
formDriver.ASYNC_TYPE = true;

formDriver.RESPONSE_SUCCESS = 0;

formDriver.login =
    function(username, password)
    {
        var data = "action=login&username="+username+"&password="+password;
        var url = formDriver.URL_REQUEST;
        var responseFunction = formDriver.loginResponse;

        AjaxManager.performAjaxRequest(formDriver.DEFAULT_METHOD,
            url, formDriver.ASYNC_TYPE,
            data, responseFunction);
    };


formDriver.loginResponse =
    function(response){
       if (response.result === formDriver.RESPONSE_SUCCESS){
           console.log("SUCCESS");
           document.location.href = "home.php";
           return;
        }

        console.log(response.result + ' ' + response.message);
        document.getElementById('login_result').textContent = response.message;
        document.getElementById('login_result').style.display = 'block';

        setTimeout(function(){
            document.getElementById('login_result').style.display = 'none';
        }, 3000);
    };

formDriver.register =
    function(username, password, password_repeat, mail)
    {
      var data = "action=register&username="+username+"&password="+password+"&password_repeat="+password_repeat+"&mail="+mail;
      var url = formDriver.URL_REQUEST;
      var responseFunction = formDriver.registerResponse;

      AjaxManager.performAjaxRequest(formDriver.DEFAULT_METHOD,
          url, formDriver.ASYNC_TYPE,
          data, responseFunction);
    };

formDriver.registerResponse =
    function(response){
        if(response.result === formDriver.RESPONSE_SUCCESS){
            console.log("SUCCESS");
            document.location.href = "home.php";
            return;
        }

        console.log(response.result + ' ' + response.message);
        document.getElementById('reg_result').textContent = response.message;

        document.getElementById('reg_result').style.display = 'block';

        setTimeout(function(){
            document.getElementById('reg_result').style.display = 'none';
        }, 3000);
    };

formDriver.settingsPassword =
    function(password, newpassword, password_repeat)
    {
        var data = "action=settingspw&password="+password+"&new_password="+newpassword+"&password_repeat="+password_repeat;
        var url = formDriver.URL_REQUEST;
        var responseFunction = formDriver.settingsPwResponse;

        AjaxManager.performAjaxRequest(formDriver.DEFAULT_METHOD,
            url, formDriver.ASYNC_TYPE,
            data, responseFunction);
    };

formDriver.settingsPwResponse =
    function(response){
        if(response.result === formDriver.RESPONSE_SUCCESS){
            console.log("SUCCESS");
            document.getElementById('settings_pass_result').textContent = 'Dati aggiornati con successo.';
        } else {
            console.log(response.result + ' ' + response.message);
            document.getElementById('settings_pass_result').textContent = response.message;
        }

        document.getElementById('settings_pass_result').style.display = 'block';
        setTimeout(function(){
            document.getElementById('settings_pass_result').style.display = 'none';
        }, 3000);
    };

formDriver.settingsMail =
    function(mail, password)
    {
        var data = "action=settingsmail&password="+password+"&mail="+mail;
        var url = formDriver.URL_REQUEST;
        var responseFunction = formDriver.settingsMailResponse;

        AjaxManager.performAjaxRequest(formDriver.DEFAULT_METHOD,
            url, formDriver.ASYNC_TYPE,
            data, responseFunction);
    };

formDriver.settingsMailResponse =
    function(response){
        if(response.result === formDriver.RESPONSE_SUCCESS){
            console.log("SUCCESS");
            document.getElementById('settings_mail_result').textContent = 'Dati aggiornati con successo.';
        } else {
            console.log(response.result + ' ' + response.message);
            document.getElementById('settings_mail_result').textContent = response.message;
        }

        document.getElementById('settings_mail_result').style.display = 'block';
        setTimeout(function(){
            document.getElementById('settings_mail_result').style.display = 'none';
        }, 3000);
    };
