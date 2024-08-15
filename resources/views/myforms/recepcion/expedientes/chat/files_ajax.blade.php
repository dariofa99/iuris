@foreach ($solicitud->files as $key => $file)
    <tr>
        <td>
            <a target="_blank" href="{{ url('/file/download', $file->id) }}">
                {{ $file->pivot->concept }} 
                 <i class="fas fa-download"> </i>
            </a>

        </td>
    </tr>
@endforeach
