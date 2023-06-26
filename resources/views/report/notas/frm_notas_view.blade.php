<table>   
    <tbody>
        @if(($segmentos)!=null)
        <tr>
            <td></td> <td></td> <td></td>
            @foreach($segmentos as $key => $segmento)
                <td colspan="5">
                  {{  $segmento->segnombre}}
                </td>
            @endforeach
        </tr>
        @endif
        <tr>
            @foreach($header as $key => $header)
            <td>
               {{ $header}}
            </td>
        @endforeach
        </tr>
       
        @foreach($data as $key => $dat)
        <tr>
            @foreach ($dat as $item)
                <td>
                    {{$item}}
                </td>
            @endforeach
        </tr>
          
        @endforeach
    </tbody>
</table>