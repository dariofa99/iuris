@if(Session::has('message-warning'))
<div class="alert alert-warning" role="alert" id="msg-warning">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-warning')}}
</div>
@endif