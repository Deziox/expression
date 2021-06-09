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
                    //console.log(data);
                    postCardArea.innerHTML = data;
                    setUpCommentIntervals();
                }
            }
        }
    }

    setTimeout(function(){
    },3000);
    let formData = new FormData(form);
    xhr.send(formData);
};

function sendComment(id){
    var form = document.getElementById('comment_form_' + id);
    var comment = form.querySelector('input');
    //console.log(comment.value + " " + id);

    let xhr = new XMLHttpRequest();
    xhr.open("POST","scripts/comment.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){
                    console.log("empty message");
                }else if(data == "nosesh"){

                }else{
                    //console.log(data);
                    //postCardArea.innerHTML = data;
                }
            }
        }
    }

    let formData = new FormData(form);
    xhr.send(formData);
}

function getRandomIntInclusive(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function setUpCommentIntervals(){
    let divs = document.getElementsByClassName("comment_area");
    let div_array = [].slice.call(divs)
    div_array.forEach(function callbackFn(element){

        setInterval(()=>{
            var post_id = element.id.split("_")[2];
            var form = document.getElementById('comment_form_' + post_id)
            var comment_id_array = element.getElementsByClassName("comment_id")
            //console.log(comment_id_array);

            if(comment_id_array) {
                var i;
                var output = "";
                for(i = 0; i < comment_id_array.length; i++){
                    output += comment_id_array[i].value
                    if(i != comment_id_array.length - 1){
                        output += ",";
                    }
                }
                //console.log(output);
                document.getElementById("comment_ids_" + post_id).value = output;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("POST","scripts/get_comments.php",true);
            xhr.onload = () => {
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        let data = xhr.response;
                        if(data == "empty"){
                            //console.log("empty");
                        }else if(data == "nosesh"){
                        }else{
                            //console.log(data);
                            element.innerHTML = data + element.innerHTML;
                        }
                    }
                }
            }
            let formData = new FormData(form);
            xhr.send(formData);

        },getRandomIntInclusive(2000,4000));
    });
    //console.log("setUpCommentIntervals " + divs);
}
