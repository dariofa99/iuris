@component('components.modal')
	
	@slot('trigger')
		{{$id}}
	@endslot

	@slot('title')
		Reportes 
	@endslot
	@slot('body')
	 <div class="row">
         <div class="col-md-12" align="center">
             <h3>Encabezado</h3>
         </div>
         <div class="col-md-12">
            <input  type="file" data-view="{{$view}}" name="encabezado_file" class="encabezado_file">
        </div>
        <div class="col-md-12">
            <div id="previewEn">
                <img style="width: 100%" src="{{asset($config_encab!=null ? $config_encab->imagen : '')}}" id="encab_img-{{$view}}">
            </div>
        </div>
        <div class="col-md-12">
            <label>Alineado</label>
            
            <select class="form-control" name="encabezado_align">
                <option @if($config_encab !=null and $config_encab->encabezado_align == 'center') selected @endif  value="center">Centrado</option>
                <option @if($config_encab !=null and $config_encab->encabezado_align == 'left') selected @endif   value="left">Izquierda</option>
                <option @if($config_encab !=null and $config_encab->encabezado_align == 'right') selected @endif    value="right">Derecha</option>
            </select>
        </div>
        <div class="col-md-12">
            <label>Tamaño</label>
        </div>
        <div class="col-md-6">
            Ancho
            <input type="text" name="encab_width" value="{{$config_encab ? $config_encab->encab_width : ''}}" class="form-control" id="encab_width-{{$view}}">
        </div>
        <div class="col-md-6">
            Alto
            <input type="text" name="encab_height" value="{{$config_encab ? $config_encab->encab_height : ''}}" class="form-control" id="encab_height-{{$view}}">
        </div>
     </div>

     <div class="row">
        <div class="col-md-12" align="center">
            <h3>Pie</h3>
        </div>
        <div class="col-md-12">
           <input  type="file" data-view="{{$view}}" name="pie_file" class="pie_file">
       </div>
       <div class="col-md-12">
           <div id="previewPie">
               <img style="width: 100%" src="{{asset($config_pie!=null ? $config_pie->imagen : '')}}" id="pie_img-{{$view}}">
           </div>
       </div>
       <div class="col-md-12">
           <label>Alineado</label>
           
        
          
           <select class="form-control" name="pie_align">
            <option @if($config_pie and $config_pie->pie_align == 'center' ) selected @endif  value="center">Centrado</option>
            <option @if($config_pie and $config_pie->pie_align == 'left' ) selected @endif value="left">Izquierda </option>
            <option @if($config_pie and $config_pie->pie_align == 'right' ) selected @endif  value="right">Derecha</option>
   
           </select>
       </div>
       <div class="col-md-12">
           <label>Tamaño</label>
       </div>
       <div class="col-md-6">
           Ancho
           <input type="text" value="{{$config_pie ? $config_pie->pie_width : ''}}" name="pie_width" class="form-control" id="pie_width-{{$view}}">
       </div>
       <div class="col-md-6">
            Alto
           <input type="text" value="{{$config_pie ? $config_pie->pie_height : ''}}" name="pie_height" class="form-control" id="pie_height-{{$view}}">
       </div>
    </div>
    

<hr>
<div class="row">
    <div class="col-md-4">
        <button type="button" class="btn btn-primary " data-dismiss="modal">Guardar</button>
    </div>
</div>


@endslot
@endcomponent
<!-- /modal -->










