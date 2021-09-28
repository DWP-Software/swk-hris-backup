function toAjax(e) {
 
        e.preventDefault();
        console.dir(e.target);
        
        var
            $link = $(e.target),
            callUrl = $link.attr("href"),
            formId = $link.data("formId"),
            onDone = $link.data("onDone"),
            onFail = $link.data("onFail"),
            onAlways = $link.data("onAlways"),
            ajaxRequest;
     
     
        ajaxRequest = $.ajax({
            type: "post",
            dataType: "json",
            url: callUrl,
            data: (typeof formId === "string" ? $("#" + formId).serializeArray() : null),
        });
     
        // Assign done handler
        if (typeof onDone === "string" && ajaxCallbacks.hasOwnProperty(onDone)) {
            ajaxRequest.done(ajaxCallbacks[onDone]);
        }
     
        // Assign fail handler
        if (typeof onFail === "string" && ajaxCallbacks.hasOwnProperty(onFail)) {
            ajaxRequest.fail(ajaxCallbacks[onFail]);
        }
     
        // Assign always handler
        if (typeof onAlways === "string" && ajaxCallbacks.hasOwnProperty(onAlways)) {
            ajaxRequest.always(ajaxCallbacks[onAlways]);
        }
     
    }