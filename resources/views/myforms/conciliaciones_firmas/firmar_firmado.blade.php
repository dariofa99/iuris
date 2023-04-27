@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="col-md-4">
    @include('msg.alerts')
</div>
    </div>
    <div class="row justify-content-center">
       
       

      <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h1>
                        Firmado correctamente
                    </h1>

                </div>

                <div align="center" class="card-body">    
               
               <img style="width: 100px;height:100px" src="{{asset("/dist/img/checkbox.png")}}" alt="">
                <hr>
                <h3 align="justify">
                    El documento será enviado a su correo electrónico cuando se concluya el proceso 
                de firma de todas las personas solicitadas.</h3>
                </div>
        </div> 
    </div>

</div>
@endsection

@push('scripts')




@endpush