@component('components.modal')
	
	@slot('trigger')
		myModal_regis_user_conciliacion
	@endslot

	@slot('title')
		Registro de usuario
	@endslot
	@slot('body')
	<div class="row">
		<div class="col-md-12">   
            <form method="POST" class="form_store" accept-charset="UTF-8" id="myformRegisUser">
                <input type="hidden" name="estado_id">
                <div class="form-group">
                    <label for="description">Estado</label>
                   @include("myforms.conciliaciones.componentes.estados_list")       
                  {{-- <select name="type_status_id" class="form-control" required>
                  
                    @foreach($types_status as $key => $type_status)
                      @if($key!=$conciliacion->estado_id)
                           <option value="{{$key}}">{{$type_status}}</option>
                      @endif
                      @endforeach 
                   </select>  --}}          
                </div>
     
                <div class="form-group">
                    <label for="nombre">Nombre del estudiante</label>
                    <input class="form-control" required  name="nombre" type="text">
                </div>
    
                <div class="form-group">
                    <label for="description">Concepto</label>
                    <textarea name="concepto" class="form-control"  rows="5"></textarea>                        
                </div> 
                    <div class="row">
                    <div class="col-md-12">
                        <table class="table" id="myReportList">
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="row" id="alertmyReportList" style="display: none">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                Debe tener en cuenta que una vez guardado el estado se generaran los archivos correspondientes en pdf y estos no se podrán modificar
                            </div>
                        </div>
                    </div>


                <div class="col-md-12">
                    <div class="form-group">
                            <br>
                    <button type="submit" class="btn btn-block btn-primary btn-sm">
                        Guardar
                    </button>
                    <button type="button" style="display:none" id="btn_ancel_upsoldoc" class="btn btn-block btn-default btn-sm">
                        Cancelar
                    </button>                            
                    </div>
                </div>
                </form>
		</div>		
	</div>
@endslot
@endcomponent
<!-- /modal -->










