<ul class="timeline" id="timeline">
  @foreach($pasos as $key => $value)
  @if($value['visible'])
  <li class="li {{$paso > $key ? 'complete' : ''}}">  
    <div class="status">
      <h6>{{$value['title']}}</h6>
    </div>
  </li>
  @endif
  @endforeach 
 {{-- <li class="li {{$paso>=1 ? 'complete' : ''}} ">
     <div class="timestamp">
      <span class="author">Abhi Sharma</span>
      <span class="date">11/15/2014<span>
    </div> 
    <div class="status">
      <h4> Solicitud </h4>
    </div>
  </li>
  <li class="li {{$paso>= 2 ? 'complete' : ''}}">
  
    <div class="status">
      <h4> Apoderado solicitante </h4>
    </div>
  </li>
  <li class="li {{$paso>=3 ? 'complete' : ''}}">

    <div class="status">
      <h4> Parte solicitada </h4>
    </div>
  </li>
  <li class="li">
  
    <div class="status">
      <h4> Radicado </h4>
    </div>
  </li> 
  --}}
</ul> 