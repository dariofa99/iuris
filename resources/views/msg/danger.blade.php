@if(Session::has('message-danger'))
<div class="alert alert-danger" role="alert">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="fa fa-times-circle" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  {{Session::get('message-danger')}}
</div> 
@endif