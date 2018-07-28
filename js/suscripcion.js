function suscribir(mail, idOrigenSus){
    loadingSusc(true);
    
  $.ajax({
     type: "POST", url: "../atp/php/suscribir.php",
     data: {pmail: mail, pidOrigenSus:idOrigenSus},
     beforeSend: function(xhr){
         console.log("suscribiendo mail idOrigenSus: "+idOrigenSus + " mail:"+mail);
     },
     error: function(obj,text,error) {
       console.log("suscribiendo mail idOrigenSus: ERROR:"+obj.responseText);
       loadingSusc(false);
       errorMP();

     },
     success: function(data, textStatus, jqXHR){
       console.log("suscribiendo mail idOrigenSus: Ok");
       loadingSusc(false);
       suscOk(true);
     }
  });
}

function loadingSusc(bool){
  if(bool){
    $("#loader-susc").css("display","block");
    $("#susc").css("display","none");
      
  } else {
    $("#loader-susc").css("display","none");
      $("#susc").css("display","inline-block");
  }
}

function suscOk(bool){
   if(bool){
    $("#loader-susc").css("display","none");
    $("#ok-susc").css("display","block");
       $("#susc").css("display","none");
    setTimeout(suscOk(false), 3000);
  } else {
    $("#loader-susc").css("display","none");
    $("#ok-susc").css("display","none");      
      $("#susc").css("display","inline-block");
  }    
}


