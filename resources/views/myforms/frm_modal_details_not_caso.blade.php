@component('components.modal')
	
	@slot('trigger')
		myModal_details_not_caso
	@endslot

	@slot('title')
		Detalles de nota:
	@endslot


	@slot('body')

	<div class="row">
		<div class="col-md-4">
			<label>Docente evaluador:</label><br>
            <span id="lblnamedocente"></span>
		</div>
		<div class="col-md-8">
			<label>Concepto:</label><br> 
            <span id="lblconceptonota"></span>
		</div>
	</div>
 	

	@endslot
@endcomponent
<!-- /modal -->










