<form id="myFormRepLegal">
    <div class="box_section">
   @include('myforms.conciliaciones.componentes.parte_solicitante_rep_legal',[     
        'section'=>'representante_legal_solicitante',
        'tipo_usuario_id'=>$tipo_usuario_id
    ])
  
    </div>

</form>
