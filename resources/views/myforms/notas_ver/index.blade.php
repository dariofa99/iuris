@extends('layouts.dashboard')

@section('titulo_general')
Notas

@endsection

@section('titulo_area')
<h3>
    @if(isset($user) and $user!=null) {{$user->name}} {{$user->lastname}}  @endif
</h3>
@endsection

@section('area_buttons')

@endsection
 

@section('area_forms') 

@include('msg.success') 

<form action="/notas/ver/estudiante" method="GET" id="myFormBuscarNotas">
    @if(auth()->user()->can('ver_notas_estudiante'))
<div class="row">
    
    <div class="col-md-4"> 

            <input required type="text" value="{{Request::get('idnumber')}}" class="form-control form-control-sm" name="idnumber">      
    </div>
    
</div>
@else
@if(Request::has('idnumber')) 
<input required type="hidden" value="{{Request::get('idnumber')}}" class="form-control form-control-sm" name="idnumber"> 
@endif
@endif
<div class="row">
    <div class="col-md-2">  
        Origen:  
            <select class="form-control" name="origen" >
                <option @if(Request::has('origen') and Request::get('origen')=='expedientes') selected @endif value="expedientes">Expedientes</option>
                <option @if(Request::has('origen') and Request::get('origen')=='conciliaciones') selected @endif value="conciliaciones">Conciliaciones</option>
                
            </select>   
    </div>  
    <div class="col-md-2">  
        Periodo:  
            <select class="form-control" name="segid" >                
                @foreach($periodos as $key => $segmento)                
                    <option @if(Request::has('segid') and Request::get('segid')==$segmento->id) selected @endif value="{{$segmento->segmento_id}}">{{$segmento->prddes_periodo}}</option>
                @endforeach
                <option value="">Ver todos</option>
            </select>    
            </div>   
            <div class="col-md-2">  
        Corte:  
            <select class="form-control" name="segid" >               
                @foreach($segmentos as $key => $segmento)                
                    <option @if(Request::has('segid') and Request::get('segid')==$segmento->id) selected @endif value="{{$segmento->segmento_id}}">{{$segmento->segnombre}}</option>
                @endforeach
                <option value="">Ver todos</option>  
            </select> 
             
            </div>  
   
    <div class="col-md-2">
        <br>
        <button class="btn btn-success btn-block btn-sm" type="submit">Buscar</button>
    </div>
</div>
</form>

<div class="row">
<div class="col-md-12 table-responsive no-padding">

    <table class="table">
        <thead>
            <th style="width: 5px">
                No.
            </th>
           <th width="1%">
            @if(Request::has('origen') and Request::get('origen')=='expedientes') Expediente
            @else
            Conciliación
            @endif  
            </th>
           
            <th width="1%">
                Periodo
            </th>
            <th width="2%">
                Corte
            </th>
            
        </thead>
    
        <tbody>
           @php
               $count = 1;
               $promedio_c=0;
               $promedio_a=0;
               $promedio_e=0;
               $contador_c=0;
               $contador_a=0;
               $contador_e=0;
           @endphp
            @forelse($notas as $key => $data)
                <tr style="border-bottom: 2px solid black">
                    <td style="width: 0%">
                        {{($count)}}
                        @php
                        $count = $count + 1;
                        @endphp
                    </td>
                    <td>
                        @if(Request::has('origen') and Request::get('origen')=='expedientes')
                        <a target="_blank" href="/expedientes/{{$data[0]['expediente']}}/edit">
                            {{$data[0]['expediente']}}
                        </a> 
                        @else
                        <a target="_blank" href="/conciliaciones/{{$data[0]['tbl_org_id']}}/edit">
                            {{$data[0]['expediente']}}
                        </a> 
                        @endif  

                      
                    </td>

                    <td>
                        {{$data[0]['periodo']}}
                    </td>
                    <td>
                        {{$data[0]['segmento']}}
                    </td>

                    <td width="50%">
                        <table class="table">
                            <thead>
                              
                                   
                                        <th width="13%">
                                            Concepto Nota 
                                        </th>
                                       
                                        <th>
                                            Nota 
                                        </th>
                                        <th>
                                            Origen
                                        </th>
                                        <th>
                                            Tipo
                                        </th>
                                        <th>
                                            Docente
                                        </th>
                                       
                              
                            </thead>
                            <tbody>
                                @php
                                     ksort($data);
                                   
                                @endphp
                                @foreach($data as $key_2 => $nota)
                                <tr @if($nota['concepto_nota_id'] == '4') style="border-bottom: 2px solid black" @endif>
                                    
                                    <td>
                                        {!! $nota['concepto_nota'] !!} 
                                    </td>
                                
                                    @php
                                    if($nota['concepto_nota_id'] == '1' and is_numeric($nota['nota']) ){
                                        $promedio_c = $promedio_c + $nota['nota'];
                                        $contador_c = $contador_c + 1;
                                    }

                                    if($nota['concepto_nota_id'] == '2' and is_numeric($nota['nota'])){
                                        $promedio_a = $promedio_a + $nota['nota'];
                                        $contador_a = $contador_a + 1;
                                    }
                                    if($nota['concepto_nota_id'] == '3' and is_numeric($nota['nota'])){
                                        $promedio_e = $promedio_e + $nota['nota'];
                                        $contador_e = $contador_e + 1;
                                    }
                                       
                                    @endphp

                                    <td  width="30%">
                                        {!!$nota['nota']!!}
                                    </td>
                                    <td>
                                        {{$nota['origen_nota']}}
                                    </td>
                                    <td>
                                        {{$nota['tipo']}}
                                    </td>
                                    <td colspan="4">
                                       {{$nota['docevname']}}
                                    </td>
                                </tr>
                              
                            @endforeach
                           
                            </tbody>
                        </table>
                        
                    </td>
                </tr>
            @empty
                
            @endforelse
          {{--   @if($contador_e>0 and Request::has('segid') and Request::get('segid')!='')
            <tr>
                <td>
                    Promedio Conocimiento
                </td>
                <td>
                    Promedio Aplicación
                </td>
                <td>
                    Promedio Ética
                </td>
                <td>
                    Promedio General
                </td>
            </tr>
            <tr>
                <td>
            
                    {{number_format($promedio_c/$contador_c,1,'.',' ' )}} 
                </td>
                <td>
                    
                    {{number_format($promedio_a/$contador_a,1,'.',' ' )}} 
                </td>
                <td>
                    {{number_format($promedio_e/$contador_e,1,'.',' ' )}} 
                </td>
                <td>
                   {{ number_format((($promedio_c/$contador_c) + ($promedio_a/$contador_a) +($promedio_e/$contador_e)) /3,1,'.',' ')}} 
                </td>
            </tr>
            @endif --}}
        </tbody>
    </table>


</div>
</div>
@include('myforms.notas_ver.modal_detalles')
@stop

