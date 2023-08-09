    // This is the bare minimum JavaScript. You can opt to pass no arguments to setup.
     var players = Plyr.setup('.js-player');
    var timer;
$(function() {
  var FADE_TIME = 150; // ms
  var TYPING_TIMER_LENGTH = 400; // ms
  var COLORS = [
    '#e21400', '#91580f', '#f8a700', '#f78b00',
    '#58dc00', '#287b00', '#a8f07a', '#4ae8c4',
    '#3b88eb', '#3824aa', '#a700ff', '#d300e7'
  ];

  // Initialize variables
  var $window = $(window);
  var $usernameInput = $('.usernameInput'); // Input for username
  var $messages = $('.messages'); // Messages area
  var $inputMessage = $('.inputMessage'); // Input message input box
  var $gomessage = $('.gomessage');

  var $loginPage = $('.login.page'); // The login page
  var $chatPage = $('.chat.page'); // The chatroom page
  var numpar=0;
  var clase;
  // Prompt for setting a username
  var username;
  var connected = false;
  var login_us = false;
  var typing = false;
  var lastTypingTime;
  var $currentInput = $usernameInput.focus();
  var usersroom={};
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var socket = io('https://testapp.udenar.edu.co:3000', {secure: true});
//var socket = io('http://testapp.udenar.edu.co:3000');
var room =  getParameterByName('room');
var user = {'username':getParameterByName('user'),
            'room':room,
            'idusuario':getParameterByName('idus'),
            'correo':getParameterByName('mail'),
            'imagen':getParameterByName('img')};

 function fecha() {
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear(),
        h = d.getHours(),
        m = d.getMinutes(),
        s = d.getSeconds(),
        mil = d.getMilliseconds();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    if (h < 10) h = '0' + h;
    if (m < 10) m = '0' + m;
    if (s < 10) s = '0' + s;
    
       var fecha = [year, month, day].join('-');
       var hora = [h, m, s, mil].join(':');
       var date = fecha + ' ' + hora
       
    return date;
}

  const addParticipantsMessage = (data) => {
    var message = '';
    numpar=data.numUsers;
    if (data.numUsers === 1) {
      message += "Hay 1 participante";
    } else {
      message += "Hay " + data.numUsers + " participantes";
    }
    //log(message);
  }

  // Sets the client's username
  const setUsername = () => {
    username = user;
    // If the username is valid
    if (username.idusuario) {
      $loginPage.fadeOut();
      $chatPage.show();
      $loginPage.off('click');
      $currentInput = $inputMessage.focus();

      // Tell the server your username
      socket.emit('add user', username);
    }
  }

  // Sends a chat message
  const sendMessage = () => {

    var message = $inputMessage.val();
    // Prevent markup from being injected into the message
    message = cleanInput(message);
    var clase='text'
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear(),
        h = d.getHours(),
        m = d.getMinutes(),
        s = d.getSeconds(),
        mil = d.getMilliseconds();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    if (h < 10) h = '0' + h;
    if (m < 10) m = '0' + m;
    if (s < 10) s = '0' + s;
    
       var fecha = [year, month, day].join('-');
       var hora = [h, m, s, mil].join(':');
       var date = fecha + ' ' + hora
    var id = String(Date.now());
        
    var data = {'id':id, 'idusuario':username.idusuario, 'message':message, 'class':clase, 'fecha': date };
          
    // if there is a non-empty message and a socket connection
    if (message && connected) {
      $inputMessage.val('');
      
      addChatMessage(data);
      // tell server to execute 'new message' and send along one parameter
      if (numpar > 1) {

        socket.emit('new message', data, room);
      }
    }


  }

    // Sends a chat message
  const sendarch = () => {
    var message = $inputMessage.val();
    // Prevent markup from being injected into the message
    message = cleanInput(message);
    var clase='text'
    var	fecha= fecha();
    var id = Date.now();
        
    var data = {'id':id, 'idusuario':username.idusuario, 'message':message, 'class':clase, 'fecha': fecha };
          
    // if there is a non-empty message and a socket connection
    if (message && connected) {
      $inputMessage.val('');

      addChatMessage(data);
      // tell server to execute 'new message' and send along one parameter
      if (numpar > 1) {

        socket.emit('new message', data, room);
      }
    }
        
 
  }

  // Log a message
    const log = (message, options) => {
    var $el = $('<li>').addClass('log').text(message);
    addMessageElement($el, options);
  }

  // Adds the visual chat message to the message list
  const addChatMessage = (data, options) => {
          //console.log(data.class);
    //busca el nombre y datos del usuario en la funcion
    var usuarioRes = UserResponse(data.idusuario);

    if (usuarioRes) {
    // Don't fade the message in if there is an 'X was typing'
    var $typingMessages = getTypingMessages(data);
    options = options || {};
    if ($typingMessages.length !== 0) {
      options.fade = false;
      $typingMessages.remove();
    }
if (data.fecha) {

var fecha = new Date(data.fecha);
var options = { day: 'numeric', month: 'long', year: 'numeric'};
var newfecha=fecha.toLocaleDateString("es-ES", options);
var hora = fecha.toLocaleTimeString('en-US');

} else {
  var hora = "Ahora";
  var newfecha="";
}

    if (data.idusuario == username.idusuario) {

      if (data.class == "text") {
    var $spanusername = $('<span class="direct-chat-name pull-right username"/>')
      .text(usuarioRes.username);
    var $spanfecha = $('<span class="direct-chat-timestamp pull-left"/>')
      .text(hora)
      .attr('data-toggle','tooltip')
      .attr('data-placement','right')
      .attr('title',newfecha);
      var $userbody = $('<div class="direct-chat-infos clearfix"/>')
      .append($spanusername,$spanfecha);
    var $imgtag = $('<img class="direct-chat-img" alt="Message User Image">')
      .attr('src','https://iuris.udenar.edu.co/thumbnails/'+usuarioRes.imagen);
    var $messageBodyDiv = $('<div class="direct-chat-text messageBody">')
      .text(data.message);

    var typingClass = data.typing ? 'typing' : '';
    var $messageDiv = $('<div class="direct-chat-msg right message" />')
      .data('username', data.idusuario)
      .addClass(typingClass)
      .append($userbody,$imgtag,$messageBodyDiv);

      } else if (data.class == "audio") {

    var $spanusername = $('<span class="direct-chat-name pull-right username"/>')
      .text(usuarioRes.username);
    var $spanfecha = $('<span class="direct-chat-timestamp pull-left"/>')
      .text(hora)
      .attr('data-toggle','tooltip')
      .attr('data-placement','right')
      .attr('title',newfecha);
      var $userbody = $('<div class="direct-chat-infos clearfix"/>')
      .append($spanusername,$spanfecha);
    var $imgtag = $('<img class="direct-chat-img" alt="Message User Image">')
      .attr('src','https://iuris.udenar.edu.co/thumbnails/'+usuarioRes.imagen);
    var audiochat = '<audio class="js-player" controls>'+
                    '<source src="'+data.message+'" type="audio/mpeg">'+
                    '</audio>';
    var $messageBodyDiv = $('<div class="direct-chat-text messageBody">')
      .html(audiochat);

    var typingClass = data.typing ? 'typing' : '';
    var $messageDiv = $('<div class="direct-chat-msg right message" />')
      .data('username', data.idusuario)
      .addClass(typingClass)
      .append($userbody,$imgtag,$messageBodyDiv);
      }
   } else {
    var $spanusername = $('<span class="direct-chat-name float-left username"/>')
      .text(usuarioRes.username);
    var $spanfecha = $('<span class="direct-chat-timestamp float-right"/>')
      .text(hora)
      .attr('data-toggle','tooltip')
      .attr('data-placement','left')
      .attr('title',newfecha);
      var $userbody = $('<div class="direct-chat-infos clearfix"/>')
      .append($spanusername,$spanfecha);
    var $imgtag = $('<img class="direct-chat-img" alt="Message User Image">')
      .attr('src','https://iuris.udenar.edu.co/thumbnails/'+usuarioRes.imagen);
    var $messageBodyDiv = $('<div class="direct-chat-text messageBody">')
      .text(data.message);

    var typingClass = data.typing ? 'typing' : '';
    var $messageDiv = $('<div class="direct-chat-msg message" />')
      .data('username', data.idusuario)
      .addClass(typingClass)
      .append($userbody,$imgtag,$messageBodyDiv);
    }
  addMessageElement($messageDiv, options);
    } //if (usuarioRes)
  } //cierra funcion

  // Adds the visual chat typing message
  const addChatTyping = (data) => {
    data.typing = true;
    data.message = 'está escribiendo...';
    addChatMessage(data);
  }

  // Removes the visual chat typing message
  const removeChatTyping = (data) => {
    getTypingMessages(data).fadeOut(function () {
      $(this).remove();
    });
  }

  // Adds a message element to the messages and scrolls to the bottom
  // el - The element to add as a message
  // options.fade - If the element should fade-in (default = true)
  // options.prepend - If the element should prepend
  //   all other messages (default = false)
  const addMessageElement = (el, options) => {
    var $el = $(el);
    // Setup default options
    if (!options) {
      options = {};
    }
    if (typeof options.fade === 'undefined') {
      options.fade = true;
    }
    if (typeof options.prepend === 'undefined') {
      options.prepend = false;
    }

    // Apply options
    if (options.fade) {
      $el.hide().fadeIn(FADE_TIME);
    }
    if (options.prepend) {
      $messages.prepend($el);
    } else {
      $messages.append($el);
    }
    $messages[0].scrollTop = $messages[0].scrollHeight;
  }

  // Prevents input from having injected markup
  const cleanInput = (input) => {
    return $('<div/>').text(input).html();
  }

  // Updates the typing event
  const updateTyping = () => {
    if (connected) {
      if (!typing) {
        typing = true;
        socket.emit('typing',room);
      }
      lastTypingTime = (new Date()).getTime();

      setTimeout(() => {
        var typingTimer = (new Date()).getTime();
        var timeDiff = typingTimer - lastTypingTime;
        if (timeDiff >= TYPING_TIMER_LENGTH && typing) {
          socket.emit('stop typing',room);
          typing = false;
        }
      }, TYPING_TIMER_LENGTH);
    }
  }

  // Gets the 'X is typing' messages of a user
  const getTypingMessages = (data) => {
    return $('.typing.message').filter(function (i) {
      return $(this).data('username') === data.idusuario;
    });
  }

  // Gets the color of a username through our hash function
  const getUsernameColor = (username) => {
    // Compute hash code
    var hash = 7;
    for (var i = 0; i < idusuario.length; i++) {
       hash = idusuario.charCodeAt(i) + (hash << 5) - hash;
    }
    // Calculate color
    var index = Math.abs(hash % COLORS.length);
    return COLORS[index];
  }

  const UserResponse = (userid) => {
    for( var i = 0; i < usersroom.length; i++){
      if ( usersroom[i].idusuario == userid) {
        return usersroom[i];
      }
    }
  }

  // Keyboard events

  $window.keydown(event => {
    // Auto-focus the current input when a key is typed
    if (!(event.ctrlKey || event.metaKey || event.altKey)) {
      $currentInput.focus();
    }
    // When the client hits ENTER on their keyboard
    if (event.which === 13) {
      if (username.idusuario) {
        sendMessage();
        socket.emit('stop typing',room);
        typing = false;
      } else {
        setUsername();
      }
    }
  });

  $inputMessage.on('input', () => {
    updateTyping();
  });

  // Click events
  // Focus input when clicking anywhere on login page
  $gomessage.click(() => {
    sendMessage();
    socket.emit('stop typing',room);
    typing = false;
    $inputMessage.focus();
  });
  // Focus input when clicking anywhere on login page
  $loginPage.click(() => {
    $currentInput.focus();
  });

  // Focus input when clicking on the message input's border
  $inputMessage.click(() => {
    $inputMessage.focus();
  });

  // Socket events

  // Whenever the server emits 'login', log the login message
  socket.on('login'+room, (data) => {
    usersroom=data.Usersroom;
    connected = true;
    login_us = true;

     var $imgtagtitle2 = $('<img class="chat-tittle-img" alt="Message User Image">')
        .attr('id', username.idusuario)
        .attr('src', 'https://iuris.udenar.edu.co/thumbnails/'+username.imagen)
        .attr('data-toggle','tooltip')
        .attr('data-placement','top')
        .attr('data-html','true')
        .attr('title','Usuario: '+username.username+' Correo: '+username.correo);
       $( ".box-chat-tittle" ).append($imgtagtitle2);
    for( var i = 0; i < usersroom.length; i++){
     if (username.idusuario != usersroom[i].idusuario) {
      if (!$('#'+usersroom[i].idusuario).length) {
     var $imgtagtitle = $('<img class="chat-tittle-img" alt="Message User Image">')
        .attr('id', usersroom[i].idusuario)
        .attr('src', 'https://iuris.udenar.edu.co/thumbnails/'+usersroom[i].imagen)
        .attr('data-toggle','tooltip')
        .attr('data-placement','top')
        .attr('data-html','true')
        .attr('title','Usuario: '+usersroom[i].username+' Correo: '+usersroom[i].correo);
      $( ".box-chat-tittle" ).append($imgtagtitle);
       }
      }
    }

    // Display the welcome message
    //var message = "Bienvenido(a) al Chat";
    //log(message, {
     // prepend: true
    //});
    addParticipantsMessage(data);
  });

  // Whenever the server emits 'new message', update the chat body
  socket.on('new message'+room, (data) => {
    addChatMessage(data);
  });

  // Whenever the server emits 'user joined', log it in the chat body
  socket.on('user joined'+room, (data) => {
    //log(data.idusuario + ' conectado(a)');
    if (login_us) {
    usersroom=data.Usersroom;
    //console.log(usersroom);
    var usuarioRes = UserResponse(data.idusuario);
    if (usuarioRes) {
      if (!$('#'+usuarioRes.idusuario).length) {
      var $imgtagtitle = $('<img class="chat-tittle-img" alt="Message User Image">')
      .attr('id', usuarioRes.idusuario)
      .attr('src', 'https://iuris.udenar.edu.co/thumbnails/'+usuarioRes.imagen)
      .attr('data-toggle','tooltip')
      .attr('data-placement','top')
      .attr('data-html','true')
      .attr('title','Usuario: '+usuarioRes.username+' Correo: '+usuarioRes.correo);
     $( ".box-chat-tittle" ).append($imgtagtitle);
      }
    }
    addParticipantsMessage(data);
  }
  });

  // Whenever the server emits 'user left', log it in the chat body
  socket.on('user left', (data) => {
    if(data.roomu == room){
    //log(data.idusuario + ' desconetado(a)');
      var j = 0;
      var k = 0;
      var p = -1;
      for( var i = 0; i < usersroom.length; i++){
        if (data.idusuario == usersroom[i].idusuario) {
          j++;
          if (k == 0) {
            p = i;
            k = 1;
            j-=1;
          }
        }
      }
    if (p != -1) {
      usersroom.splice(p, 1);
    }

    if (j < 1) {
      $('#'+data.idusuario).remove();
    }
    //addParticipantsMessage(data);
    removeChatTyping(data);
    }
  });

  // Whenever the server emits 'typing', show the typing message
  socket.on('typing'+room, (data) => {
    addChatTyping(data);
  });

  // Whenever the server emits 'stop typing', kill the typing message
  socket.on('stop typing'+room, (data) => {
    removeChatTyping(data);
  });

  socket.on('disconnect', () => {
    //log('has sido desconectado(a)');
    $('.chat-box-title').text('Chat - Desconectado: ');
    login_us = false;
    for( var i = 0; i < usersroom.length; i++){
      if (username.idusuario != usersroom[i].idusuario) {
        $('#'+usersroom[i].idusuario).remove();
      }else {
        $('#'+usersroom[i].idusuario)
          .css('border-color', '#ff3434')
          .attr('title','Mensajes Offline');
      }
    }
    usersroom={};
  });

  socket.on('reconnect', () => {
    $('.chat-box-title').text('Chat - en linea: ');
    $('#alert-conex-node').remove();
    //log('has sido reconectado(a)');
    $('.chat-tittle-img').remove();
    if (username.idusuario) {
      socket.emit('add user', username);
    }
  });

  socket.on('reconnect_error', () => {
    //log('intento de reconexión ha fallado');
    if (!$('#alert-conex-node').length) {
      var $mgalert = '<div id="alert-conex-node" class="alert alert-warning alert-dismissable" style="margin: 0px 0px 0px 0px; padding: 8px; position: relative;" >'+
                '<button type="button" class="close" data-dismiss="alert" style="right: 0px;" >&times;</button>'+
                '<strong>¡Cuidado!</strong> El intento de reconexión ha fallado'+
              '</div>';
      $( "#alertas" ).append($mgalert);
    } else {
      $( "#alertas" ).hide( "slow" );
      $( "#alertas" ).show( "slow" );

    }
  });

//boton mas opciones oculta y muestra las opciones adicionales
$('#opcions-msj').hover(function(){
  if ($('#message').is(":hover") == false && $('#btn-message').is(":hover") == false) {
    $(".opc-add").show();
    $('#opc-msj-btn').css('padding-left','0px')
                    .css('padding-right','0px');
  } else {
   $(".opc-add").hide();
    $('#opc-msj-btn').css('padding-left','4px')
                    .css('padding-right','4px');
 }

});

$('#message').hover(function(){
    $(".opc-add").hide();
    $('#opc-msj-btn').css('padding-left','4px')
                    .css('padding-right','4px');
});
$('#message').focus(function(){
    $(".opc-add").hide();
    $('#opc-msj-btn').css('padding-left','4px')
                    .css('padding-right','4px');
});
$('#btn-message').hover(function(){
    $(".opc-add").hide();
    $('#opc-msj-btn').css('padding-left','4px')
                    .css('padding-right','4px');
});
$('#opc-msj-btn').hover(function(){
   $(".opc-add").show();
   $('#opc-msj-btn').css('padding-left','0px')
                    .css('padding-right','0px');
});



//boton para enviar archivos o imagenes muestra y oculta elementos
$('#up-file').click(function(){
  $('#opc-msj-btn').css('display','none');
  $('#btn-voz').css('display','none');
  $('#up-img').css('display','none');
  $('#up-file').css('display','block');
  $('#message').css('display','none');
  $('#files-up').attr('accept','.doc, .docx, .pdf, .xls, .xlsx');
  $('#text-file').attr('placeholder','Nombre del archivo...');
  $('#text-file').css('display','block');
  $('#btn-message').css('display','none');
  $('#sutmit-file').css('display','block');
  $('#btn-cancelar').css('display','block');
  $("input[name='archivos']").trigger("click");
});

$('#up-img').click(function(){
  $('#opc-msj-btn').css('display','none');
  $('#btn-voz').css('display','none');
  $('#up-img').css('display','block');
  $('#up-file').css('display','none');
  $('#message').css('display','none');
  $('#files-up').attr('accept','image/*');
  $('#text-file').attr('placeholder','Nombre de la imagen...');
  $('#text-file').css('display','block');
  $('#btn-message').css('display','none');
  $('#sutmit-file').css('display','block');
  $('#btn-cancelar').css('display','block');
  $("input[name='archivos']").trigger("click");
});

$('#files-up').change(function(){
  var nomarc = $('#files-up').val();
  nomarc = nomarc.split('\\');
    $('#text-file').val(nomarc[nomarc.length-1]);
  if (nomarc[nomarc.length-1].length >= 30) {
    $('#text-file').attr("title",nomarc[nomarc.length-1]);

  }

});

$('#btn-voz').click(function(){
  $('#btn-voz').css('display','block');
  $('#up-img').css('display','none');
  $('#up-file').css('display','none');
  $('#startBtn').css('display','block');
  $('#stopBtn').css('display','none');
  $('#btn-message').css('display','none');
  $('#btn-cancelar').css('display','block');
  $('#opc-msj-btn').css('display','none');
  $('#message').css('display','none');
  $('#text-file').css('text-align','center');
  $('#text-file').val('00:00');
  $('#text-file').css('background-color','#fff');
  $('#text-file').css('display','block');
});

$('#startBtn').click(function(){
  $('#startBtn').css('display','none');
    $('#stopBtn').css('display','block');
});
$('#stopBtn').click(function(){
  $('#btn-voz').css('display','block');
  $('#btn-voz').prop( "disabled", true );//activar cuando se envia el archivo
  $('#up-img').css('display','none');
  $('#up-file').css('display','none');
  $('#startBtn').css('display','none');
  $('#stopBtn').css('display','none');
  $('#btn-message').css('display','none');
  $('#btn-cancelar').css('display','none');
  $('#opc-msj-btn').css('display','none');
  $('#message').css('display','none');
  $('#text-file').css('text-align','inherit');
  $('#text-file').css('background-color','#eee');
  $('#text-file').val('');
  $('#text-file').css('display','none');
  $('#progress-chat').css('display','block');
});

$('#sutmit-file').click(function(){
  if ($('#files-up').attr('accept') == "image/*") { //determina el tipo de opcion ejecutada
      $('#up-img').prop( "disabled", true );//activar cuando se envia el archivo
      $('#up-img').css('display','block');
  } else {
      $('#up-file').prop( "disabled", true );//activar cuando se envia el archivo
      $('#up-file').css('display','block');
  }

  $('#sutmit-file').css('display','none');
  $('#btn-voz').css('display','none');
  $('#btn-message').css('display','none');
  $('#btn-cancelar').css('display','none');
  $('#opc-msj-btn').css('display','none');
  $('#message').css('display','none');
  $('#text-file').css('text-align','inherit');
  $('#text-file').css('background-color','#eee');
  $('#text-file').val('');
  $('#text-file').css('display','none');
  $('#progress-chat').css('display','block');
});

//deja todo como estaba en el foter chat
$('#btn-cancelar').click(restaurar);

function restaurar() {
    $('#text-file').css('text-align','inherit');
  $('#text-file').css('background-color','#eee');
  $('#btn-voz').css('display','block');
  $('#up-img').css('display','block');
  $('#up-file').css('display','block');
  $('#message').css('display','block');
  $('#text-file').css('display','none');
  $('#btn-message').css('display','block');
  $('#sutmit-file').css('display','none');
  $('#btn-cancelar').css('display','none');
  $('#startBtn').css('display','none');
  $('#stopBtn').css('display','none');
  $('#opc-msj-btn').css('display','block');
  $('#progress-chat').css('display','none');
  $('#btn-voz').prop( "disabled", false );
  $(".opc-add").show();
  $('#files-up').val('');
  $('#text-file').val('');
}

//gabar audio
        var recorder ="";

        $('#startBtn').on('click', function (e) {
            if (recorder == "") {
                recorder = new MP3Recorder({
                bitRate: 64
                }), timer;
            }
            e.preventDefault();
            $('#estado').text('grabando...');
            var btn = $(this);
            recorder.start(function () {
                var minutos = 0;
                //start timer,
                var seconds = 0, updateTimer = function(){
                 if (seconds > 59) {
                        minutos++
                        seconds = 0;
                        console.log(minutos+':'+seconds)
                    }
                    $('#text-file').val(seconds < 10 ? '0' + minutos + ':0' + seconds : '0' + minutos + ':' +seconds);
                };
                timer = setInterval(function () {
                    seconds++;
                    updateTimer();
                }, 1000);
                updateTimer();
                //disable start button
                btn.attr('disabled', true);
                $('#stopBtn').removeAttr('disabled');
            }, function () {
                alert('No se puede hacer uso de su micrófono en este momento');
            });
        });


        $('#stopBtn').on('click', function (e) {
            e.preventDefault();
            recorder.stop();
            $(this).attr('disabled', true);
            $('#estado-voz').text('procesando...');
            $('#startBtn').removeAttr('disabled');
            //get MP3
            clearInterval(timer);
            recorder.getMp3Blob(function (blob) {
                //var blobUrl = window.URL.createObjectURL(blob);
                blobToDataURL(blob, function(url){

        var formData = new FormData();
        formData.append('file', blob);
        var token = $("#token").attr('content');
        formData.append('token', token);
      $.ajax({
        url: "/chat/upload/audio",
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        headers: { 'X-CSRF-TOKEN' : token },
       xhr: function(){
        //upload Progress
        var xhr = $.ajaxSettings.xhr();
        //xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        if (xhr.upload) {
            xhr.upload.addEventListener('progress', function(event) {
                var percent = 0;
                var position = event.loaded || event.position;
                var total = event.total;
                if (event.lengthComputable) {
                    percent = Math.ceil(position / total * 100);
                }
        $('#estado-voz').text('Enviando '+percent+'%');
         $('#progress-file').css('width', percent+'%');
            }, true);
        }

        return xhr;
    },
    mimeType:"multipart/form-data"
      }).done(function(res){
        var data = jQuery.parseJSON( res );
      if (formData && connected) {
      $inputMessage.val('');
      data.message=url;
      addChatMessage(data);
      // tell server to execute 'new message' and send along one parameter
      if (numpar > 1) {

        socket.emit('new message', data, room);
      }
    }
        $('#estado-voz').text('Enviado');
        $('#progress-file').css('width', '0%');
        restaurar();
         players = Plyr.setup('.js-player');

  }).fail( function() { //detecta el error al guardar los mensajes
    restaurar();
          log('Error php---');
      });


                });
            }, function (e) {
                alert('Error al enviar el mensaje de voz');
                console.log(e);
                restaurar();
            });
        });

        function blobToDataURL(blob, callback) {
            var a = new FileReader();
            a.onload = function (e) {
                callback(e.target.result);
            }
            a.readAsDataURL(blob);
        }

$("#audios").on("mousedown", ".plyr__control", function(){
      if ($( this ).attr("class") != "plyr__control plyr__control--pressed") {
        for (var i = 0; players.length > i; i++) {
         players[i].ready=true;
         players[i].pause();
        }
    }
});
setUsername();

});
