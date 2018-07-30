function errorSusc() {
    $('#informarErrorSusc').modal('show');
}
function loadingSusc(bool, id) {
if (bool) {
    
    $("#"+id+" [name=loader-susc]").css("display","block");
    $("#"+id+" [name=susc]").css("display","none");
      
  } else {
    $("#"+id+" [name=loader-susc]").css("display","none");
      $("#"+id+" [name=susc]").css("display","inline-block");
  }
}

function suscOk(bool, id){
   if(bool){
    $("#"+id+" [name=loader-susc]").css("display","none");
    $("#"+id+" [name=ok-susc]").css("display","block");
    $("#"+id+" [name=susc]").css("display","none");       
    setTimeout(function() { suscOk(false, id); }, 1500);
  } else {
    $("#"+id+" [name=loader-susc]").css("display","none");
    $("#"+id+" [name=ok-susc]").css("display","none");      
      $("#"+id+" [name=susc]").css("display","inline-block");
      $("#"+id+" [name=mail]").val("");      
  }    
}


function suscribir(id, idOrigenSus) {
    loadingSusc(true, id);
    var mail = $("#"+id+' [name=mail]').val();
    
  $.ajax({
     type: "POST", url: "/atp/php/suscribir.php",
     data: {pmail: mail, pidOrigenSus:idOrigenSus},
     beforeSend: function(xhr){
         console.log("suscribiendo mail idOrigenSus: "+idOrigenSus + " mail:"+mail);
     },
     error: function(obj,text,error) {
       console.log("suscribiendo mail idOrigenSus: ERROR:"+obj.responseText);
       loadingSusc(false, id);
       errorSusc();

     },
     success: function(data, textStatus, jqXHR){
       console.log("suscribiendo mail idOrigenSus: Ok");         
       loadingSusc(false, id);         
       suscOk(true, id);         
     }
  });
}



