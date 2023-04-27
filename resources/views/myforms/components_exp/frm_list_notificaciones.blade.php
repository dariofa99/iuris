
<table class="table">
<thead>
                               <th>Fecha</th><th>Mensaje</th><th></th>
                               </thead>
                               <tbody>
                               @foreach ($expediente->logs as $log)
<tr>
    <td>{{$log->created_at}}</td>
    <td>
   {{$log->description}}
    </td>
    {{-- <td width="20%">
    <button class="btn btn-primary btn_log_edit" data-id="{{$log->id}}"><i class="fa fa-edit"></i></button>
     <button class="btn btn-danger btn_log_delete" data-id="{{$log->id}}"><i class="fa fa-trash"></i></button>
    </td> --}}
    </tr>
@endforeach 
{{-- 
<tr>
    <td>03-09-2020</td>
    <td>
        Necesito que me envie los documentos faltantes
    </td>
    </tr> --}}
                               </tbody>


</table>