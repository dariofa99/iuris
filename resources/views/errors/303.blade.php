@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="col-md-4">
    @include('msg.alerts')
</div>
    </div>
    <div class="row justify-content-center">
       
       

      <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-1" style="font-size: 58px">
                            <i class="fa fa-info-circle"></i>
                        </div>
                        <div class="col-md-10" style="text-align:justify">
                            <h1>                        
                                La dirección que estas buscando ya no se encuentra disponible.                        
                            </h1>
                        </div>
                    </div>
                    

                </div>

                <div align="center" class="card-body">    
              <h3>
                Si crees que esto es un error, comunicate con nosotros.
                </h3> 
               {{-- <img style="width: 100px;height:100px" src="{{asset("/dist/img/checkbox.png")}}" alt="">
                --}} <hr>
                 
                </div>
        </div> 
    </div>

</div>
@endsection

@push('scripts')




@endpush