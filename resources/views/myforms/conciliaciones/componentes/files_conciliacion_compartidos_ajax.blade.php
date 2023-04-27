
@foreach ($compartidos as $key => $compartido)
    
<div class="row" style="word-break: break-all;">
    <div class="col-md-2">                        
            {{$compartido->clave}}                        
    </div>
    <div class="col-md-8">
        <small>
           {{url('/firmar/pdf/verify/')}}/{{$compartido->token}}  
        </small>
    </div>
    <div class="col-md-1">
      <button data-key="{{$key}}" class="btn_show_files  btn btn-warning btn-sm">
          <i class="fa fa-file-pdf-o"></i>
        </button>
    </div>
    <div class="col-md-1">
       <button data-key="{{$key}}" class="btn_show_data  btn btn-default btn-sm"> <i class="fa fa-plus"></i></button>
    </div>
</div>

<div class="row" style="word-break: break-all;">
    <div class="col-md-2">                        
                               
    </div>
    <div class="col-md-8 content_fd" id="files-{{$key}}" style="display: none">
        <small>
           <ul>
            @foreach ($compartido->files as $file)
            <li>
                {{$file->original_name}}
            </li>
            @endforeach
            
            
           </ul>
        </small>
    </div>

    <div class="col-md-8 content_fd" id="data-{{$key}}" style="display: none">
        <small>
           <label>
            Fecha expiración: {{getSmallDateWithHour($compartido->fecha_exp_token)}}
           </label><br>

           <label>
            Destinatario: {{$compartido->category->ref_nombre}}
           </label><br>
            
           <label>
            Medio: {{$compartido->means->ref_nombre}}
           </label>

        </small>
    </div>
   
</div>
@endforeach