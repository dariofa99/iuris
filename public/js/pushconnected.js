    // This is the bare minimum JavaScript. You can opt to pass no arguments to setup.

$(function() {


  var numpar_pcio=0;
  // Prompt for setting a username
  var user_pcio;
  var connected_pcio = false;
  var login_us_pcio = false;

  var usersroom_pcio={};

  var userinfo_pcio = $('#connectedData').val();
  userinfo_pcio=JSON.parse(userinfo_pcio);
   
  /*
  userinfo_pcio.username=userinfo_pcio.username.replace('ñ', '&ntilde;');
  userinfo_pcio.username=userinfo_pcio.username.replace('Ñ', '&Ntilde;');
  userinfo_pcio.username=userinfo_pcio.username.replace('á', '&aacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('é', '&eacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('í', '&iacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('ó', '&oacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('ú', '&uacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('Á', '&aacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('É', '&Eacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('Í', '&Iacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('Ó', '&Oacute;');
  userinfo_pcio.username=userinfo_pcio.username.replace('Ú', '&Uacute;');
  */
  userinfo_pcio.username=userinfo_pcio.username.replace('ñ', 'n');
  userinfo_pcio.username=userinfo_pcio.username.replace('Ñ', 'N');
  userinfo_pcio.username=userinfo_pcio.username.replace('á', 'a');
  userinfo_pcio.username=userinfo_pcio.username.replace('é', 'e');
  userinfo_pcio.username=userinfo_pcio.username.replace('í', 'i');
  userinfo_pcio.username=userinfo_pcio.username.replace('ó', 'o');
  userinfo_pcio.username=userinfo_pcio.username.replace('ú', 'u');
  userinfo_pcio.username=userinfo_pcio.username.replace('Á', 'A');
  userinfo_pcio.username=userinfo_pcio.username.replace('É', 'E');
  userinfo_pcio.username=userinfo_pcio.username.replace('Í', 'I');
  userinfo_pcio.username=userinfo_pcio.username.replace('Ó', 'O');
  userinfo_pcio.username=userinfo_pcio.username.replace('Ú', 'U');
  

  var room_pcio = 'lybra_6B5C1248215ACB86';
  userinfo_pcio.room = room_pcio;


  const addParticipantsMessage_pcio = (data) => {
    var message = '';
    numpar_pcio=data.numUsers;
    if (data.numUsers === 1) {
      message += "Hay 1 participante";
    
      
    } else {
      message += "Hay " + data.numUsers + " participantes";
    }
    //console.log(message);
  }

  // Sets the client's username
  const setUsername_pcio = () => {
    user_pcio = userinfo_pcio;
    // If the username is valid
    if (user_pcio.idusuario) {
      // Tell the server your username
      socket.emit('add user', user_pcio);
    }
  }

 
  const UserResponse_pcio = (userid) => {
    for( var i = 0; i < usersroom_pcio.length; i++){
      if ( usersroom_pcio[i].idusuario == userid) {
        return usersroom_pcio[i];
      }
    }
  }


function connectedUserAppend_pcio(userconnected) {
  var $imgcircleuserconnected = $('<img  alt="User Avatar" class="img-size-50 mr-3 img-circle">')
    .attr('src',userconnected.imagen);  
  var $nameh3userconnected  = $('<h3 class="dropdown-item-title"/>')
    .text(userconnected.username);
  var $divmediabody  = $('<div class="media-body"/>')
    .append($nameh3userconnected);
  var $divmedia  = $('<div class="media" />')
    .css({"white-space": "nowrap", "overflow": "hidden"})
    .append($imgcircleuserconnected,$divmediabody);
  var $acontentus = $('<a class="dropdown-item userconntend_pcio user_login-'+userconnected.idusuario+'" />')
    .attr('href','#')
    .append($divmedia);
  $( "#list_users_login" ).append($acontentus);

}

  // Socket events

  // Whenever the server emits 'login', log the login message
  socket.on('login'+room_pcio, (data) => {
    usersroom_pcio=data.Usersroom;
    connected_pcio = true;
    login_us_pcio = true;

    //coloca el html en el nav
    connectedUserAppend_pcio(user_pcio); //no muestra mi mismo login

    for( var i = 0; i < usersroom_pcio.length; i++){
      if (user_pcio.idusuario != usersroom_pcio[i].idusuario) {
        if (!$('.user_login-'+usersroom_pcio[i].idusuario).length) {
     
          //coloca el html en el nav
          connectedUserAppend_pcio(usersroom_pcio[i]);

        }
      }
    }
  
    addParticipantsMessage_pcio(data); // si el contenedor tiene mensajes solo añade el usuaario conectado al chat
    $('.lbl_chatCountUsers').text($('.userconntend_pcio').length);
  
  });

  // Whenever the server emits 'user joined', log it in the chat body
  socket.on('user joined'+room_pcio, (data) => {
    //log(data.idusuario + ' conectado(a)');
    if (login_us_pcio) {
      usersroom_pcio=data.Usersroom;
      //console.log(usersroom_pcio);
      var usuarioRes = UserResponse_pcio(data.idusuario);
      if (usuarioRes) {
        if (!$('.user_login-'+usuarioRes.idusuario).length) {

          //coloca el html en el nav
          connectedUserAppend_pcio(usuarioRes);


        }
      }
      addParticipantsMessage_pcio(data);
    }
    $('.lbl_chatCountUsers').text($('.userconntend_pcio').length);
  });

  // Whenever the server emits 'user left', log it in the chat body
  socket.on('user left', (data) => {
    if(data.roomu == room_pcio){
      //console.log(data.idusuario + ' desconetado(a)');
  
      var j = 0;
      var k = 0;
      var p = -1;
      for( var i = 0; i < usersroom_pcio.length; i++){
        if (data.idusuario == usersroom_pcio[i].idusuario) {
          j++;
          if (k == 0) {
            p = i;
            k = 1;
            j-=1;
          }
        }
      }
    if (p != -1) {
     usersroom_pcio.splice(p, 1);
    }

    if (j < 1) {
      //esperar 5 segundos para verificar si verdaderamete se ha desconectado  (problema recarga de pagina o cambio de opciones sidebar)
      setTimeout(function() {
        removeuser_pcio(data.idusuario);
       //$('.user_login-'+data.idusuario).remove();
      }, 5000);
    }
    
    }
  });

  function removeuser_pcio(idusuario) {
    var controluser_pcio = 0;
    usersroom_pcio.forEach(value => {
      if (value.idusuario == idusuario) {
        controluser_pcio++;
      }
    });
    if (controluser_pcio == 0) {
      $('.user_login-'+idusuario).remove();
      $('.lbl_chatCountUsers').text($('.userconntend_pcio').length);
    }
    
    
  }

  socket.on('disconnect', () => {
    //console.log('has sido desconectado(a)');
    $('.lbl_chatCountUsers').text('?');
    login_us_pcio = false;
    for( var i = 0; i < usersroom_pcio.length; i++){
      if (user_pcio.idusuario != usersroom_pcio[i].idusuario) {
        $('.user_login-'+usersroom_pcio[i].idusuario).remove();
      }else {
        //$('#'+usersroom_pcio[i].idusuario)
        //  .css('border-color', '#ff3434')
        //  .attr('title','Mensajes Offline');
      }
    }
    usersroom_pcio={};
  });

  socket.on('reconnect', () => {
    $('.userconntend_pcio').remove();
    
    //$('.chat-box-title').text('En linea: ');
    //$('#alert-conex-node').remove();
    //console.log('has sido reconectado(a)');
    //$('.chat-tittle-img').remove();
    if (user_pcio.idusuario) {
      socket.emit('add user', user_pcio);
    }
  });

  socket.on('reconnect_error', () => {
      $('.lbl_chatCountUsers').text('?');
    //console.log('intento de reconexión ha fallado');
    if (!$('#alert-conex-node').length) {
      var $mgalert = '<div id="alert-conex-node" class="alert alert-warning alert-dismissable" style="margin: 0px 0px 0px 0px; padding: 8px; position: relative;" >'+
                '<button type="button" class="close" data-dismiss="alert" style="right: 0px;" >&times;</button>'+
                '<strong>¡Cuidado!</strong> El intento de reconexión ha fallado'+
              '</div>';
      //$( "#alertas" ).append($mgalert);
    } else {
      //$( "#alertas" ).hide( "slow" );
      //$( "#alertas" ).show( "slow" );

    }
  });

 
  setUsername_pcio();


});
