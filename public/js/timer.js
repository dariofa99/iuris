var x = setInterval(displayTime, 1000);

  function displayTime(){
    var seconds = (getSeconds());      
    if(seconds <= 0 && $("#soli_type_status_id").val() == 158){
      clearInterval(x); 
      $("#clock").html("<i class='fa fa-info-circle'> </i> Se ha cancelado la solicitud por límite de tiempo vencido.");
    }else if(seconds >= 0 && $("#tiempo_espera").val()!=undefined && $("#tiempo_espera").val()!='' && $("#soli_type_status_id").val() == 156){      
      var time = timer(seconds);  
      if(time.seconds>0)  $("#clock").text(time.time);
      $("#clockdate").show();
    }else if(seconds<=0){    
      clearInterval(x); 
      $("#clock").html("<i class='fa fa-info-circle'> </i> Se ha cancelado la solicitud por límite de tiempo vencido.");
   
    }   
  }

function getSeconds(){
  if($("#tiempo_espera").val()!=''){
    var ini = moment($("#tiempo_espera").val()).format('HH:mm:ss');;
    ini = moment.duration(ini).asSeconds() 
    var now = moment().format('HH:mm:ss');
    now = moment.duration(now).asSeconds();  
    return ini - now; 
  }
  return 0;
 
}

function timer(seconds) { 
       var days        = Math.floor(seconds/24/60/60);
       var hoursLeft   = Math.floor((seconds) - (days*86400));
       var hours       = ('0'+ Math.floor(hoursLeft/3600)).slice(-2);
       var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
       var minutes     = ("0"+ Math.floor(minutesLeft/60)).slice(-2);
       var remainingSeconds = ("0"+(seconds % 60)).slice(-2);     
      if (seconds <= 0) {       
         let request = {'type_status_id':158,'type_category_id':159}  
         $("#clock").html("<i class='fa fa-info-circle'> </i> Se ha cancelado la solicitud por límite de tiempo vencido.");
         updateSolicitud(request,$("#solicitud_id").val()) 
         clearInterval(x);                         
       } else {
           seconds--;
       }
       return {
            'time': hours + ":" + minutes + ":" + remainingSeconds,
            'seconds':seconds
       };     
      
}
//Esta funcion cuenta el temporizador hacia arriba
/*function secondsToString(seconds) {
  var hour = Math.floor(seconds / 3600);
  hour = (hour < 10)? '0' + hour : hour;
  var minute = Math.floor((seconds / 60) % 60);
  minute = (minute < 10)? '0' + minute : minute;
  var second = seconds % 60;
  second = (second < 10)? '0' + second : second;
  return hour + ':' + minute + ':' + second;
}*/