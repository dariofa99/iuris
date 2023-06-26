@component('components.modal')
	
	@slot('trigger')
		{{$id}}
	@endslot

	@slot('title')
		Reportes 
	@endslot
	@slot('body')
	 
    <div class="row">
        <div class="col-md-2 col-md-offset-4">
            <center>
                <input type="number" @if($configuraciones) value="{{$configuraciones->top}}" @endif  name="top" value="1.27" placeholder="1.27" style="width: 50px" >
            </center>
        </div>
    </div> 

    <div class="row">
        <div class="col-md-4">
            <input type="number" @if($configuraciones) value="{{$configuraciones->left}}" @endif name="left" value="1.27" placeholder="1.27" style="width: 50px" class="pull-right">
        </div>
        <div class="col-md-2">
            <center>
                <i class="fa fa-file" style="font-size: 55px; margin:10px">  </i>
            </center>
        </div>
        <div class="col-md-4">
            <input type="number" name="right" @if($configuraciones) value="{{$configuraciones->right}}" @endif  value="1.27" placeholder="1.27" style="width: 50px" class="pull-left">
        </div>
    </div>


    <div class="row">
        <div class="col-md-2 col-md-offset-4">
            <center>
            <input type="number" @if($configuraciones) value="{{$configuraciones->bottom}}" @endif name="bottom"  value="1.27" placeholder="1.27" style="width: 50px">
        </center>
        </div>
    </div>

<hr>
<div class="row">
    <div class="col-md-4">
        <button type="button" class="btn btn-primary " data-dismiss="modal">Guardar</button>
    </div>
</div>

{{-- 

            <div class="col-md-5">

           
            <table class="tabl">
                <tbody>
                    <tr>
                        <td>

                        </td>
                        <td>
                            <input type="number" placeholder="1.27">
                        </td>
                        <td>

                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="number" placeholder="1.27">
                        </td>
                        <td>
                            <center>
                                <i class="fa fa-file" style="font-size: 25px; margin:10px">  </i>
                            </center>
                           
                        </td>
                        <td>
                            <input type="number" placeholder="1.27">
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td>
                            <input type="number" placeholder="1.27">
                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
                   
        </div> --}}
@endslot
@endcomponent
<!-- /modal -->










