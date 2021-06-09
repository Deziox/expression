const chatForm = document.querySelector(".chat-typing-area");
const chatSearchForm = document.querySelector("#chat-search-form")
inputField = chatForm.querySelector(".chat-input-field");
sendBtn = chatForm.querySelector(".chat-send");
chatBox = document.querySelector(".chat-box");

document.getElementById("chat-search").onclick = () =>{
    //alert("search test");
    let xhr = new XMLHttpRequest();
    xhr.open("POST","scripts/search_chat.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "nosesh"){
                }else if(data == "empty"){
                }else if(data == "nouser"){
                    chatForm.querySelector("#receiver_id").value = ""
                }else{
                    chatForm.querySelector("#receiver_id").value = data
                    chatBox.innerHTML = "";
                }
            }
        }
    }

    let formData = new FormData(chatSearchForm);
    xhr.send(formData);
}

document.getElementById("chat-send").onclick = () =>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","scripts/send_chat.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){
                    console.log("empty message");
                }else if(data == "nouser"){
                    console.log("no valid user in search field");
                }else{
                    inputField.value = "";
                }
            }
        }
    }

    let formData = new FormData(chatForm);
    xhr.send(formData);
}

$('.chat-typing-area input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        let xhr = new XMLHttpRequest();
        xhr.open("POST","scripts/send_chat.php",true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    if(data == "empty"){
                        console.log("empty message");
                    }else if(data == "nouser"){
                        console.log("no valid user in search field");
                    }else{
                        inputField.value = "";
                    }
                }
            }
        }

        let formData = new FormData(chatForm);
        xhr.send(formData);
        return false;
    }
});

$('#chat-search-form input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        let xhr = new XMLHttpRequest();
        xhr.open("POST","scripts/search_chat.php",true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    if(data == "nosesh"){
                    }else if(data == "empty"){
                    }else if(data == "nouser"){
                        chatForm.querySelector("#receiver_id").value = ""
                    }else{
                        chatForm.querySelector("#receiver_id").value = data
                        chatBox.innerHTML = "";
                    }
                }
            }
        }

        let formData = new FormData(chatSearchForm);
        xhr.send(formData);
        return false;
    }
});

document.getElementById("close-chat").onclick = () => {
    setTimeout(function(){
        chatBox.innerHTML = "";
        document.getElementById("message_ids").value = "";
        document.getElementById("receiver_id").value = "";
    },1000);
}

setInterval(()=>{
    if(chatForm.querySelector("#receiver_id").value != "") {
        var message_id_array = document.getElementsByClassName("message_id")

        if (message_id_array) {
            var i;
            var output = "";
            for (i = 0; i < message_id_array.length; i++) {
                output += message_id_array[i].value
                if (i != message_id_array.length - 1) {
                    output += ",";
                }
            }
            console.log(output);
            document.getElementById("message_ids").value = output;
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "scripts/get_chat.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    if (data == "empty") {
                        console.log("empty");
                    } else if (data == "nosesh") {

                    } else {
                        chatBox.innerHTML = data + chatBox.innerHTML;
                    }
                }
            }
        }
        let formData = new FormData(chatForm);
        xhr.send(formData);
    }
},2000);
