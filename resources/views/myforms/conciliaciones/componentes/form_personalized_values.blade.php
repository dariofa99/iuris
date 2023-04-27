@foreach ($data as $item)
<tr>
    <td>
       {{ getAditionalDataByShortName($item->short_name,'pdf_reportes')->name}}
    </td>
    <td>
        <input name="short_name[]"  value="{{$item->short_name}}" type="hidden">
  
        <input name="personalized_value[]" 
        value="{{$reporte->getDataValWShort($item->short_name) ? $reporte->getDataValWShort($item->short_name)->value : ''}}"
        type="text" class="form-control form-control-sm">
    </td>
</tr> 
@endforeach