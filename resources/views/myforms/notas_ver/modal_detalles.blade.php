@component('components.modal_dynamic')


	@slot('size')
		modal-dialog modal-lg
	@endslot
	
	@slot('trigger')
		myModal_notas_details
	@endslot 

	@slot('title')
		Detalles
	@endslot

 
	@slot('body')

    <div class="row">
        <div class="col-md-12">
            <textarea name="text" class="form-control form-control-sm" readonly disabled id="detalles_nota"  rows="5"></textarea>
        </div>
    </div>


	@endslot
@endcomponent
<!-- /modal -->










