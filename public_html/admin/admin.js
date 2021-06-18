userAccountArea = document.querySelector(".admin-users-area");

window.onload = function(){
    userAccountArea.innerHTML = "";
    load_users();
};

function load_users(){
    let xhr = new XMLHttpRequest();
    xhr.open("POST","../admin/admin_get_users.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){
                }else if(data == "nosesh"){
                }else{
                    userAccountArea.innerHTML = data;
                }
            }
        }
    }

    xhr.send();
}

function disable_account(id){
    let xhr = new XMLHttpRequest();
    xhr.open("GET","../admin/admin_disable_account.php?uid=" + id,true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){
                }else if(data == "nosesh"){
                }else{
                    load_users();
                }
            }
        }
    }

    xhr.send();
}

function reinstate_account(id){
    let xhr = new XMLHttpRequest();
    xhr.open("GET","../admin/admin_reinstate_account.php?uid=" + id,true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){
                }else if(data == "nosesh"){
                }else{
                    load_users();
                }
            }
        }
    }

    xhr.send();
}