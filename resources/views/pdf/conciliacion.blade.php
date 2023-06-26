<html>
<head>

@if($encabezado!=null)
  <link rel="stylesheet" href="{{public_path('/css/pdf.css')}}" type="text/css" media="all" />  
@endif

  <style>
    
   
    #header { position: fixed; left: 0px; top:-165px; right: 0px;  background-color: rgb(255, 255, 255) }
    #footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px; }
    #header .page:after { content: counter(page) " de " counters(pages); }
    table img { width: 100px; height: 100px; }
    .table { text-align: center }
    #watermark {
                position: fixed;              
                top: 8cm;
                width:    100%;
                height:   8cm;
                z-index:  -1000;
                transform: rotate(-45deg);
            }
    .table_firm{
      margin-top: 50px;
      font-size: 12px;
      width: 100%      
      }

      .table_tr_firm{
      border-bottom: 1px solid gray;     
      }
  </style>
<body class="content_pdf" style="margin:{{$margin}} !important">

    @if(!isset($is_preview))
    <div id="watermark">
         <img src="{{public_path('/dist/img/pdf_marca_agua.png')}}" height="100%" width="100%" />
     </div>
 @endif
 @if($encabezado!=null)
 <div id="header">
   @include('pdf.partials.header')
</div>
@endif
 
@if($pie_conf!=null)
<div id="footer">
  @include('pdf.partials.footer')
</div> 
@endif
  
  <div id="content">
    {!! $pdf !!}
    
  </div>

  @if(isset($users) and $users!=null and count($users) > 0)

  <div id="firmantes">
    <small>
      <table class="table_firm">
        <tbody>
          <tr>
            <td>
              <b>Firmantes:</b>
             
            </td>
          </tr>
          <tr>
        @foreach ($users as $user)     
          <tr>
            <td>{{$user->name}} {{$user->lastname}}</td>
          </tr>

          <tr>
            <td>Tipo de firma:<b> {{$user->tipo_pdf_firmante()->where([
              'conciliacion_id'=>$user->pivot->conciliacion_id,
              'tipo_firma_id'=>$user->pivot->tipo_firma_id,
            ])->first()->ref_nombre}}</b></td>
          </tr>
          
          <tr>
            <td class="table_tr_firm">Firmado el {{getSmallDateWithHour($user->pivot->fecha_firma)}} en calidad de 
             <b> {{$user->tipo_conciliacion()->where([
                'conciliacion_id'=>$user->pivot->conciliacion_id,
                'tipo_usuario_id'=>$user->pivot->tipo_usuario_id,
              ])->first()->ref_nombre}}</b>
              </td>
          </tr>
                   
      @endforeach
    </tbody>
      </table>
    </small>
  

  </div> 
  @endif

</body>
</html>