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
  var typing = false;
  var lastTypingTime;
  var $currentInput = $usernameInput.focus();

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var socket = io('https://betaiuris.udenar.edu.co:3000', {secure: true});
var room =  getParameterByName('room');
var idusuario= getParameterByName('us');
var usuario = getParameterByName('user');
var correo = getParameterByName('mail');
var imagen = getParameterByName('img');

  const addParticipantsMessage = (data) => {
    var message = '';
    numpar=data.numUsers;
    if (data.numUsers === 1) {
      message += "Hay 1 participante";
    } else {
      message += "Hay " + data.numUsers + " participantes";
    }
    log(message);
  }

  // Sets the client's username
  const setUsername = () => {
    username = usuario;
    // If the username is valid
    if (username) {
      $loginPage.fadeOut();
      $chatPage.show();
      $loginPage.off('click');
      $currentInput = $inputMessage.focus();

      // Tell the server your username
      socket.emit('add user', username, room);
    }
  }

  // Sends a chat message
  const sendMessage = () => {
    var message = $inputMessage.val();
    // Prevent markup from being injected into the message
    message = cleanInput(message);
    clase='texto';
        $.ajax({//realiza una copia de seguridad a la bd
          type: 'POST',
          url: '/chat/registro',
          data: {'username':username, 'message':message, 'class':clase },
          dataType: 'json',
          beforeSend: function(xhr){
    //  $("#wait").css("display", "block");
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
    },
    success: function(data){
    // if there is a non-empty message and a socket connection
    if (message && connected) {
      $inputMessage.val('');
      addChatMessage(data);
      // tell server to execute 'new message' and send along one parameter
     // if (numpar > 1) {

        socket.emit('new message', data, room);
     // }
    }
        }
      }).fail( function() { //detecta el error al guardar los mensajes
          log('Error en la conexción---');
      });//cierra consulta ajax;
  }

  // Log a message
    const log = (message, options) => {
    var $el = $('<li>').addClass('log').text(message);
    addMessageElement($el, options);
  }

  // Adds the visual chat message to the message list
  const addChatMessage = (data, options) => {
    // Don't fade the message in if there is an 'X was typing'
    var $typingMessages = getTypingMessages(data);
    options = options || {};
    if ($typingMessages.length !== 0) {
      options.fade = false;
      $typingMessages.remove();
    }

var fecha = new Date(data.fecha);
var options = { day: 'numeric', month: 'long', year: 'numeric'};
var newfecha=fecha.toLocaleDateString("es-ES", options);
var hora = fecha.toLocaleTimeString('en-US');


    if (data.username == usuario) {

    var $spanusername = $('<span class="direct-chat-name pull-right username"/>')
      .text(data.username);
    var $spanfecha = $('<span class="direct-chat-timestamp pull-left"/>')
      .text(hora)
      .attr('data-toggle','tooltip')
      .attr('data-placement','right')
      .attr('title',newfecha);
      var $userbody = $('<div class="direct-chat-info clearfix"/>')
      .append($spanusername,$spanfecha);
    var $imgtag = $('<img class="direct-chat-img" src="../dist/img/user1-128x128.jpg" alt="Message User Image">');
    var $messageBodyDiv = $('<div class="direct-chat-text messageBody">')
      .text(data.message);

    var typingClass = data.typing ? 'typing' : '';
    var $messageDiv = $('<div class="direct-chat-msg right message" />')
      .data('username', data.username)
      .addClass(typingClass)
      .append($userbody,$imgtag,$messageBodyDiv);

   } else {
    var $spanusername = $('<span class="direct-chat-name pull-left username"/>')
      .text(data.username);
    var $spanfecha = $('<span class="direct-chat-timestamp pull-right"/>')
      .text(hora)
      .attr('data-toggle','tooltip')
      .attr('data-placement','left')
      .attr('title',newfecha);
      var $userbody = $('<div class="direct-chat-info clearfix"/>')
      .append($spanusername,$spanfecha);
    var $imgtag = $('<img class="direct-chat-img" src="../dist/img/user1-128x128.jpg" alt="Message User Image">');
    var $messageBodyDiv = $('<div class="direct-chat-text messageBody">')
      .text(data.message);

    var typingClass = data.typing ? 'typing' : '';
    var $messageDiv = $('<div class="direct-chat-msg message" />')
      .data('username', data.username)
      .addClass(typingClass)
      .append($userbody,$imgtag,$messageBodyDiv);
    }
  addMessageElement($messageDiv, options);
  }

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
      return $(this).data('username') === data.username;
    });
  }

  // Gets the color of a username through our hash function
  const getUsernameColor = (username) => {
    // Compute hash code
    var hash = 7;
    for (var i = 0; i < username.length; i++) {
       hash = username.charCodeAt(i) + (hash << 5) - hash;
    }
    // Calculate color
    var index = Math.abs(hash % COLORS.length);
    return COLORS[index];
  }

  // Keyboard events

  $window.keydown(event => {
    // Auto-focus the current input when a key is typed
    if (!(event.ctrlKey || event.metaKey || event.altKey)) {
      $currentInput.focus();
    }
    // When the client hits ENTER on their keyboard
    if (event.which === 13) {
      if (username) {
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
    connected = true;
    // Display the welcome message
    var message = "Bienvenido(a) al Chat";
    log(message, {
      prepend: true
    });
    addParticipantsMessage(data);
  });

  // Whenever the server emits 'new message', update the chat body
  socket.on('new message'+room, (data) => {
    addChatMessage(data);
  });

  // Whenever the server emits 'user joined', log it in the chat body
  socket.on('user joined'+room, (data) => {
    log(data.username + ' conectado(a)');
    var $imgtagtitle = $('<img class="chat-tittle-img" src="../dist/img/user1-128x128.jpg" alt="Message User Image">')
    .attr('id', data.username);
    $( ".box-chat-tittle" ).append($imgtagtitle);
    addParticipantsMessage(data);
  });

  // Whenever the server emits 'user left', log it in the chat body
  socket.on('user left', (data) => {
    if(data.roomu == room){
    log(data.username + ' desconetado(a)');
    $('#'+data.username).remove();
    addParticipantsMessage(data);
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
    log('has sido desconectado(a)');
  });

  socket.on('reconnect', () => {
    log('has sido reconectado(a)');
    if (username) {
      socket.emit('add user', username, room);
    }
  });

  socket.on('reconnect_error', () => {
    log('intento de reconexión ha fallado');
  });

setUsername();

});
