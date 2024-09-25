<ul class="timeline" id="timeline">
  @foreach($pasos as $key => $value)
 
  @if($value['visible'])
  <li @if(isset($value['toltip'])) data-tippy-content="{{$value['toltip']}}" @endif  class="li {{$paso > $key ? 'complete' : ''}}">  
    <div class="status">
      <h6 >{{$value['title']}}</h6>
    </div>
  </li>
  @endif
  @endforeach 

</ul> 
