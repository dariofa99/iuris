       <div class="col-md-12">
           <h4>Información sobre discapacidad</h4>
       </div>
       <div class="col-md-{{ isset($col) ? $col : '12' }}">
           <div class="form-group">
               <label for="pbepersondiscap">¿Es una persona con discapacidad?<span class="ast_required">*</span></label>
               <select {{ isset($disabled) ? $disabled : '' }} name="pbepersondiscap" id="pbepersondiscap"
                   class="form-control form-control-sm required" required>
                   <option value="">Seleccione...</option>
                   <option value="1" {{(isset($user) and $user->pbepersondiscap != 1) ?: "selected" }}>Si
                   </option>
                   <option  value="0" {{(isset($user) and $user->pbepersondiscap != 0) ?: "selected" }}>No
                   </option>
               </select> 
           </div>
       </div>
       <div style="display: {{(isset($user) and $user->pbepersondiscap == 0) ? "none":"block" }}"  class="col-md-{{ isset($col) ? $col : '12' }} discaform">
           <div class="form-group">
               <label for="has_apoyo">¿Cuenta con persona de apoyo?<span class="ast_required">*</span></label>
               <select {{ isset($disabled) ? $disabled : '' }} name="has_apoyo" id="has_apoyo"
                   class="form-control form-control-sm required" required disabled>
                   <option value="">Seleccione...</option>
                   <option {{(isset($user) and $user->has_apoyo != 1) ?: "selected" }}  value="1">Si
                   </option>
                   <option {{(isset($user) and $user->has_apoyo != 0) ?: "selected" }}  value="0">No
                   </option>
               </select>
           </div>

           <div class="has_apoyo form-group alert alert-info">
            Obligaciones y consideraciones relacionadas con la privacidad en el trámite de apoyo a personas con discapacidad por parte de la persona que brinda el apoyo. Es fundamental que estas pautas sean comprendidas y respetadas para garantizar el bienestar y la autonomía del solicitante.           
            
               <ol>
                   <li><strong> Confidencialidad y Privacidad:</strong><br>
                    Se compromete a respetar y tratar con la máxima reserva toda la información personal, médica o confidencial relacionada con el solicitante y que eventualmente pueda conocer. Igualmente tenga en cuenta que está facultado(a) para compartir información privada con terceros sin el consentimiento expreso del solicitante.

                   </li>
                   <li><strong>Respeto a la Autonomía:</strong><br>
                    Se compromete a respetar la autonomía del solicitante permitiéndole tomar sus propias decisiones, teniendo en cuenta que el apoyo brindado hace parte de los ajustes razonables que se generan en favor del usuario.
                </li>
               </ol>

               <div>
                   <input {{(isset($user) and $user->has_apoyo != 1) ?: "checked" }} type="checkbox" disabled required class="required" name="acept_ter" id="acept_ter">
                   Comprendo los terminos y condiciones 
                   <span class="ast_required">*</span>

               </div>


           </div>
       </div>



       @include('myforms.components_user.aditional_data', [
           'data' => getReferencesDataBySection('discapacidad', 'users'),
           'discaform' => 'discaform',
       ])
