postCardArea = document.querySelector(".post-card-area");
var interval_array = [];

window.onload = function(){
    postCardArea.innerHTML = "";
    var $_GET = {};
    if(document.location.toString().indexOf('?') !== -1) {
        var query = document.location
            .toString()
            .replace(/^.*?\?/, '')
            .replace(/#.*$/, '')
            .split('&');
        for (var i = 0, l = query.length; i < l; i++) {
            var aux = decodeURIComponent(query[i]).split('=');
            $_GET[aux[0]] = aux[1];
        }
        load_profile_posts($_GET['id']);
    }
};

function load_profile_posts(id){
    let xhr = new XMLHttpRequest();
    xhr.open("POST","scripts/get_profile_posts.php?id=" + id,true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "empty"){
                }else if(data == "no_user"){
                    window.location.replace("/user.php");
                }else if(data == "nosesh"){
                }else{
                    postCardArea.innerHTML = data;

                    let divs = document.getElementsByClassName("comment_area");
                    let div_array = [].slice.call(divs)
                    div_array.forEach(function callbackFn(element){

                        interval_array.push(setInterval(()=>{
                            var post_id = element.id.split("_")[2];
                            var form = document.getElementById('comment_form_' + post_id)
                            var comment_id_array = element.getElementsByClassName("comment_id")

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
                                        }else if(data == "nosesh"){
                                        }else{
                                            element.innerHTML = data + element.innerHTML;
                                        }
                                    }
                                }
                            }
                            let formData = new FormData(form);
                            xhr.send(formData);

                        },Math.floor(Math.random() * (4000 - 2000 + 1) + 2000)));
                    });
                }
            }
        }
    }

    // setTimeout(function(){
    // },3000);
    let formData = new FormData(form);
    xhr.send(formData);
}



