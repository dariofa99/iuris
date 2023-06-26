   
   <table>
       <tbody>
            <tr>
                <td>
                    Expediente
                </td>
                <td>
                    Consultante
                </td>
                <td>
                    Estudiante
                </td>
                <td>
                    Tipo Consulta
                </td>
                <td>
                    Fecha			
                </td>
                <td>
                    Estado
                </td>
               
            </tr>
            @foreach ($expedientes as $key => $expediente)
                <tr>
                   
                    <td>
                        {{ $expediente->expid }}
                    </td>
                    <td>
                        {{  FullName($expediente->solicitante->name, $expediente->solicitante->lastname )  }}
                    </td>
                    <td>
                        {{  FullName($expediente->estudiante->name, $expediente->estudiante->lastname )  }}
                  
                    </td>
                    <td>
                        @if  ($expediente->exptipoproce_id =='1')  
                        Asesoría
                  @elseif($expediente->exptipoproce_id =='2')
                        Seguimiento
                   @else
                        Defensa de Oficio 
                  @endif 
                    </td>
                    <td>
                        {{  \Carbon\Carbon::parse($expediente->getAsignacion()->fecha_asig)->diffForHumans() }}
                       
                        ({{  \Carbon\Carbon::parse($expediente->getAsignacion()->fecha_asig)->format("d-m-Y") }})
                   
                    </td>
                    <td>
                        {{$expediente->estado->nombre_estado }}
                    </td>
                   
                </tr>
            @endforeach
        </tbody>
    </table>
