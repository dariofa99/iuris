@component('components.modal')
	@slot('trigger')
		myModal_audiencia_salas_alternas
	@endslot
	@slot('title')
		Salas alternas
	@endslot
	@slot('body')

	<div class="row" id="info_modal_salas_alternas_audiencia_list">
        {{--aqui va la ifnroacion de las salas carada por js--}}

	</div>


    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <input type="button" value="Añadir sala alterna" class="btn btn-success btn-block btn-sm" id="nueva_sala_alterna_audiencia" data-id="{{$conciliacion->id}}" data-cont="0" data-numusers="{{$numusers}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <br>
                <input type="button" value="Activar salas" class="btn btn-primary btn-block btn-sm" id="activar_salas_alternas_audiencia"  data-id="{{$conciliacion->id}}">
                <button type="button" style="display:none" id="btn_cancel_upsoldoc" class="btn btn-block btn-default btn-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>


	@endslot
@endcomponent
<!-- /modal -->










