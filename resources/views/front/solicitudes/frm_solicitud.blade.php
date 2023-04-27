<input id='solicitud_id' value="{{$solicitud->id}}" name='solicitud_id'  type="hidden">
                         
         <div class="row">
                            <div class="col-md-4">	
                                 <div class="form-group"><label for="idnumber">Número de documento</label>
                                    <input disabled id='idnumber' value="{{$solicitud->idnumber}}" name='idnumber'  type="text" class="form-control">
                                
                            </div>  
                            </div> <!-- /.md12-->   
                            <div class="col-md-4">
                                <div class="form-group"><label for="estrato_id">Estrato</label>
                                  <select disabled name="estrato_id" id="estrato_id" class="form-control required" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($estrato as $key => $tipo)
                                    @if($key!=9)
                                    <option {{$key==$solicitud->estrato_id ? 'selected':''}} value="{{$key}}">{{$tipo}}</option>@endif
                                    @endforeach
                                  </select>
                                </div>
                            </div> 

                 
                                  
                    </div> <!-- /.row -->
                      <div class="row">  
                        <div class="col-md-4">
                            <div class="form-group "><label for="name">Nombres</label>
                            <input id='name' disabled value="{{$solicitud->name}}" name='name' type="text" class="form-control" placeholder="Tu nombre">
                        
                            </div>
                            </div> 

                        <div class="col-md-4">
                            <div class="form-group "><label for="lastname">Apellidos</label>
                            <input id='lastname' disabled value="{{$solicitud->lastname}}" name='lastname' type="text" class="form-control" placeholder="Tu apellido">
                            
                            </div>
                        </div>        
                    </div>

                    <div class="row">                    
                         <div class="col-md-4">
                            <div class="form-group "><label for="tel1">Número de contacto</label>
                            <input id='tel1' disabled value="{{$solicitud->tel1}}" name='tel1' type="text" class="form-control" placeholder="Tu apellido">
                          </div>
                        </div>
                    </div>

                      <div class="row">
                        <div class="col-md-8">
                            <div class="form-group has-feedback"><label for="description">Descripción</label>
                            <textarea disabled name="description" id="description" class="form-control" rows="6">{{$solicitud->description}}</textarea>
                            </div>
                        </div>
                    </div>