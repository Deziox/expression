let unread = [];

setInterval(()=>{

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/chat_notification.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (data == "empty") {
                    console.log("no recent messages");
                } else if (data == "nosesh") {
                } else {
                    let notif_toast = document.getElementsByClassName("show")
                    if (notif_toast.length < 1) {
                        var i;
                        let unread_users = data.split(",");
                        let count = 0;
                        for (i = 0; i < unread_users.length; i++) {
                            if(unread.indexOf(unread_users[i]) < 0){
                                unread.push(unread_users[i]);
                                count++;
                            }
                        }
                        if(count > 0) {
                            var notif_text = "<h3>New message(s) from ";

                            for (i = 0; i < unread.length; i++) {
                                notif_text += "@" + unread_users[i]
                                if (i != unread_users.length - 1) notif_text += ", "
                            }
                            notif_text += "</h3>";
                            document.querySelector("#notification-body").innerHTML = notif_text;
                            $('.toast').toast('show');
                        }
                    }
                }
            }
        }
    }

    let formData = new FormData(chatForm);
    xhr.send(formData);
},2000);

setInterval(()=>{
    unread = []
},600555);

