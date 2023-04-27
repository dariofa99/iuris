$("button").click(function(){

var url = $(this).attr("id");


$.get(url, function(data){
    $('#divc').html(data);   
   });

});