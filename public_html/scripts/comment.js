


// setInterval(()=>{
//     var message_id_array = document.getElementsByClassName("message_id")
//
//     if(message_id_array) {
//         var i;
//         var output = "";
//         for(i = 0; i < message_id_array.length; i++){
//             output += message_id_array[i].value
//             if(i != message_id_array.length - 1){
//                 output += ",";
//             }
//         }
//         console.log(output);
//         document.getElementById("message_ids").value = output;
//     }
//
//     let xhr = new XMLHttpRequest();
//     xhr.open("POST","./search_chat.php",true);
//     xhr.onload = () => {
//         if(xhr.readyState === XMLHttpRequest.DONE){
//             if(xhr.status === 200){
//                 let data = xhr.response;
//                 if(data == "empty"){
//
//                 }else if(data == "nosesh"){
//
//                 }else{
//                     console.log(data);
//                     chatBox.innerHTML += data;
//                 }
//             }
//         }
//     }
//     let formData = new FormData(form);
//     xhr.send(formData);
// },2000);

