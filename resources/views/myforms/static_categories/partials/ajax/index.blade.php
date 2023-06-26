
<table class="table" >
    <thead>
    <th>
    ID
    </th>
    <th>
       Nombre largo
    </th>
    <th>
        Nombre
    </th>
    <th>
    Uso en
    </th>
    <th>
       Sección
    </th>
    <th>
        Primer opción ID
    </th>
    </thead>
    <tbody>
    @foreach ($categories as $category )
        <tr>
        <td>
        {{$category->id}}
        </td>
        <td>
            {{$category->display_name}}
            </td>
            <td>
                {{$category->name}}
                </td>
        <td>
        {{$category->getCategory()}} 
        </td>
        <td>
            {{$category->section}} 
        </td>
        <td>
            {{$category->options[0]->id}} 
            </td>
        <td>
        <button class="btn btn-primary btn-sm btn_edit_con_category" data-id="{{$category->id}}">
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