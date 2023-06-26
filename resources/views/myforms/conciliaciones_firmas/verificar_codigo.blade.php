@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="col-md-4">
    @include('msg.alerts')
</div>
    </div>
    <div class="row justify-content-center">
       
       

      <div class="col-md-4">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">    
                <form id="myFormSearchSolicitud" action="{{route("firmar.verify")}}" method="POST">   
                    @csrf 
                         <input type="hidden" name="token_firma" value="{{$token}}">
                        <div class="row">         
                            <div class="col-md-12">
                            <div id="con_inse" class="form-group has-feedback"><label for="number_">Número de cédula</label>
                            <input id='number_' name='idnumber' value="{{ old('idnumber') }}"  required type="text" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-refresh form-control-feedback"></span>
                            <div class="invalid-feedback">
                            
                            </div>
                            </div>
                            </div>   
                            
                            <div class="col-md-12">
                              <div id="con_inse" class="form-group has-feedback"><label for="codigo">Código de verificación</label>
                              <input id='number_' name='codigo' value="{{ old('codigo') }}" required type="text" class="form-control form-control-sm">
                              <span class="nav-icon fa fa-refresh form-control-feedback"></span>
                              <div class="invalid-feedback">
                              
                              </div>
                              </div>
                              </div> 

                        </div>
                        <div class="row">        
                            <div class="col-md-12">
                            <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-sm btn-block">
                            Consultar</button>
                            </div>
                            </div>    
                        </div>
                </form> 
                <hr>
                 
                </div>
        </div> 
    </div>

</div>
@endsection

@push('scripts')




@endpush