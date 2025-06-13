@component('components.b4.modal_large')
	
	@slot('trigger')
		myModal_general
	@endslot

	@slot('title')
	<label id="titulo_modal"></label>	
	@endslot

  @slot('footer')

  @endslot
	@slot('body')
@include('msg.ajax.success')



	<div id="content-data" class="row">
        <div class="col-md-12">
    
        </div>

    </div>



	@endslot
@endcomponent
<!-- /modal -->










