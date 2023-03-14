{{-- <div class="row">
 
    <div class="col-md-2 col-md-offset-10">
        @if(($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194))
            @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
                || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar']))))
            <button type="button" id="btn_create_document" class="btn btn-primary btn-sm pull-right">Agregar anexo</button>
            @endif
        @endif
        
    </div>
</div> --}}


<div class="row" id="content_files_conciliacion" style="display: block">
    <div class="col-md-1">
        
        <button type="button" id="btn_create_document_" data-category="233" class="btn btn-primary btn-sm  btn_create_document">Agregar documento</button>
          
     </div>

    <div class="col-md-1 col-md-offset-10">
       @if(((currentUser()->hasRole('diradmin') ||  currentUser()->hasRole('amatai') ||  currentUser()->hasRole('secretaria'))))
        @if($conciliacion->estado_id==194 || $conciliacion->estado_id==225)       
        <button id="btn_radicar_conci" class="btn btn-success" data-estado="178">Radicar</button>
        @endif

        @if($conciliacion->estado_id==178)       
        <button id="btn_notificar_conci_est" class="btn btn-success" data-estado="{{$conciliacion->estado_id}}">Notificar</button>
        @endif
        @endif
    </div>
   {{--  <div class="col-md-12">
        <table class="table" id="myReportPdfListPrincipal">
            <thead>
                <th>
                    Concepto
                </th>
                <th>
                    Archivo
                </th>
                <th>
                    Subido por
                </th>
                <th>
                    Acciones
                </th>
            </thead>
            <tbody>
                <tr>
                <td>
                
                </td>
                </tr>
            </tbody>
        </table>
    </div> --}}

    
        <div class="col-md-12">
            <table class="table" id="myReportPdfListPrincipal">
               <thead>
                <th>
                    Concepto
                </th>
                <th>
                    Archivo
                </th>
                <th>
                    Subido por
                </th>
                <th>
                    Acciones
                </th>
               </thead>
                <tbody>
                    @include('myforms.conciliaciones.componentes.anexos_ajax',[
                        'category'=>233
                    ])
                </tbody>
            </table>
        </div>
   

</div>

<div class="row" id="content_mail_notificacion_conciliacion" style="display: none">
    <div class="col-md-12">
      <h4>
        Notificando
    </h4>
    </div>
    <div class="col-md-9" id="">
        <form id="myFormEnviarCorreoConciliacion">
           <input type="hidden" name="cuerpo_correo">
            <div class="form-group">             
              <div class="input-group">
                <div class="input-group-addon">Destinatario</div>
                {{-- <div id="content_mail">
                    <input type="text" class="form-control" id="exampleInputAmount">
                    <div class="content_mail">
                        @foreach ($conciliacion->getUsersByType(196) as $key => $user)
                            <span>{{$user->email}} <b> X </b></span>
                        @endforeach
                    </div>
                </div> --}}
                <select required name="correo_send[]" class="selectpicker form-control" multiple>                    
                    @foreach ($conciliacion->getUsersByType(196) as $key => $user)
                            <option selected value="{{$user->email}}">{{$user->email}}</option>
                    @endforeach
                </select>
                  
                {{--  --}}                
              </div>
            </div>
            <div class="form-group">
                <label for="cuerpo_correo">Cuerpo del correo</label>
                <div id="content_form_correo" class="summernote">
                    
                </div>
               {{--  <div contentEditable="true" id="content_form_correo" class="form-control editable">
                    
                </div> --}}
              </div>
            <hr>
            <button type="submit" class="btn btn-primary">Enviar</button>
          </form>
    </div>
</div>
