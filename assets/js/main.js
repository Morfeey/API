window.addEventListener("load", function () {
    f();
    window.addEventListener("keyup", (e) => {
        if (e.keyCode === 13) {
            f();
        }
    });
});

function f() {
    function status(response) {
        if (response.status >= 200 && response.status < 300) {
            return Promise.resolve(response)
        } else {
            return Promise.reject(new Error(response.statusText))
        }
    }
    function json (response) {
        try {
            return Promise.resolve(response.json());
        }catch (e) {
            Promise.reject(new Error(e));
        }
    }

    var headers = new Headers();
    headers.append("Language", "ru");
    headers.append("Version", "last");
    headers.set("content-type", "application/json");

    fetch("API", {
        method:"POST",
        headers: headers
    })
        .then(status)
        .then((response)=> {
            var head = {};
            response.headers.forEach( (value, key)=>{
                head[key] = value;
            } );

            $("#result>#headers").text(JSON.stringify(head, "", 4), true);
            response.text().then( result => $("#result>#data").html(result));
        })
        .catch((error)=>{
            document.getElementById("error").innerHTML = error;
        });
}