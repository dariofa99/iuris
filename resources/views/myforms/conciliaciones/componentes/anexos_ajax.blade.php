@foreach($conciliacion->files()->where(['category_id'=>$category])->get() as $key => $file)
                    <tr>
                        <td>
                            {{$file->pivot->concepto}}
                        </td>
                        <td>
                            {{$file->original_name}}
                        </td>
                        <td>
                            {{$file->userinconciliacion[0]->name}} {{$file->userinconciliacion[0]->lastname}}
                        </td>
                     
                            <td width="4%">
                                <a class="btn btn-block btn-primary" toltip="Vista previa del  documento" target="_blank" href="/conciliaciones/download/file/{{$file->pivot->file_id}}">
                                <i class="fa fa-download"></i>
                                </a>

                                <a class="btn btn-block btn-danger btn_delete_anxcon" data-file="{{$file->pivot->file_id}}" toltip="Elimianr" href="#">
                                    <i class="fa fa-trash"></i>
                                    </a>

                                </td> 
                            


                       
                    </tr>
                @endforeach