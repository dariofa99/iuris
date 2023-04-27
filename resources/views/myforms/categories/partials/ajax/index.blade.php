
<table class="table" >
    <thead>
    <th>
    Nombre
    </th>
    <th>
    Nombre en BD
    </th>
    <th>
    Uso en
    </th>
    
    </thead>
    <tbody>
    @foreach ($categories as $category )
        <tr>
        <td>
        {{$category->name}}
        </td>
        <td>
            {{$category->short_name}}
        </td>
        <td>
        {{$category->getCategory()}} 
        </td>
        <td>
        <button class="btn btn-primary btn-sm btn_edit_category" data-id="{{$category->id}}">
        Editar
        </button>
         <button class="btn btn-danger btn-sm btn_delete_category" data-id="{{$category->id}}">
        Eliminar
        </button>
       
    
        </td>
        </tr>
    @endforeach
    
    
    </tbody>
    </table>