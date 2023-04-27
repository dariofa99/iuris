@if(Session::has('message-danger'))
<div class="alert alert-danger" role="alert" id="msg-danger">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-danger')}}
</div> 
@endif