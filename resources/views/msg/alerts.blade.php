@if(Session::has('message-success'))
<div class="alert alert-success" role="alert" id="msg-success">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-success')}}
</div>
@endif

@if(Session::has('message-info'))
<div class="alert alert-info" role="alert" id="msg-info">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
  <span class="sr-only">Info:</span>
  {{Session::get('message-info')}}
</div>
@endif

@if(Session::has('message-danger'))
<div class="alert alert-danger" role="alert">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-danger')}}
</div> 
@endif

@if(Session::has('message-warning'))
<div class="alert alert-warning" role="alert" id="msg-warning">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-warning')}}
</div>
@endif