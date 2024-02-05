function AjaxManager(){}

AjaxManager.getAjaxObject =
    function(){
        var xmlHttp = null;
        try {
            xmlHttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP"); //IE (recent versions)
            } catch (e) {
                try {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); //IE (older versions)
                } catch (e) {
                    xmlHttp = null;
                }
            }
        }
        return xmlHttp;
    };

AjaxManager.performAjaxRequest =
    function(method, url, isAsync, dataToSend, responseFunction){
        var xmlHttp = AjaxManager.getAjaxObject();
        if (xmlHttp === null){
            window.alert("Il tuo browser non supporta AJAX!");
            return;
        }

        xmlHttp.open(method, url, isAsync);

        xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xmlHttp.onreadystatechange = function (){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                var data = JSON.parse(xmlHttp.responseText);
                responseFunction(data);
            }
        };

        xmlHttp.send(dataToSend);
    };