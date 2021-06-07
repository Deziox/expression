postCardArea = document.querySelector(".post-card-area");

window.onload = function(){
    postCardArea.innerHTML = "";

    let xhr = new XMLHttpRequest();
    xhr.open("POST","scripts/get_posts.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){

                }else if(data == "nosesh"){

                }else{
                    console.log(data);
                    postCardArea.innerHTML = data;
                }
            }
        }
    }

    setTimeout(function(){
    },3000);
    let formData = new FormData(form);
    xhr.send(formData);
};
