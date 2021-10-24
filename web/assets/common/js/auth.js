function diffUserDoDiffThing(managerDo, enterpriseDo){
    $.get({
        url: "service/get_user_type.php",
        success: function(res) {
            if(res === "manager") managerDo()
            if(res === "enterprise") enterpriseDo()
        }
    })
}