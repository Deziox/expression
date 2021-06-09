const form = document.querySelector(".create-post");

document.getElementById("post").onclick = () =>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","scripts/create.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                console.log(data);
                if(data == "err_title"){

                }else if(data == "err_file"){

                }else if(data == "err_filetype"){

                }
            }
        }
    }

    let formData = new FormData(form);
    xhr.send(formData);
}