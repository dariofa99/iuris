@if(Session::has('message-success'))
<div class="alert alert-success" role="alert" id="msg-success">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-success')}}
</div>
@endif