@component('components.b4.modal_large')

    @slot('trigger')
        myModal_edit_notas
    @endslot

    @slot('title')
    @endslot


    @slot('body')
        @php
            if (!isset($ajax)) {
                $nota_conocimiento = '0.0';
                $nota_etica = '0.0';
                $nota_aplicacion = '0.0';
                $nota_final = '0.0';
                $nota_conocimientoid = 0;
                $nota_eticaid = 0;
                $nota_aplicacionid = 0;
                $nota_concepto = 0;
                $nota_conceptoid = 0;
                if ($periodo and $segmento) {
                    /*  if(count($tabla->get_has_nota_final())>0){
                $nota_conocimiento = number_format($tabla->get_has_nota_final()['nota_conocimiento']['nota'],1,'.','.');
                $nota_conocimientoid = $tabla->get_has_nota_final()['nota_conocimiento']['id'];
                $nota_etica = number_format($tabla->get_has_nota_final()['nota_etica']['nota'],1,'.','.');
                $nota_eticaid = $tabla->get_has_nota_final()['nota_etica']['id'];
                $nota_aplicacion = number_format($tabla->get_has_nota_final()['nota_aplicacion']['nota'],1,'.','.');
                $nota_aplicacionid = $tabla->get_has_nota_final()['nota_aplicacion']['id'];
                $nota_concepto = ($tabla->get_has_nota_final()['nota_concepto']['nota']);
                $nota_conceptoid = $tabla->get_has_nota_final()['nota_concepto']['id'];
                $nota_final = number_format($tabla->get_has_nota_final()['nota_final']['nota'],1,'.','.');
            }elseif ($tabla->get_nota_corte('conocimiento')) {
                $nota_conocimiento = number_format($tabla->get_nota_corte('conocimiento')['nota'],1,'.','.');
                 $nota_conocimientoid = $tabla->get_nota_corte('conocimiento')['id'];
                 $nota_etica = number_format($tabla->get_nota_corte('etica')['nota'],1,'.','.');
                 $nota_eticaid = $tabla->get_nota_corte('etica')['id'];
                 $nota_aplicacion = number_format($tabla->get_nota_corte('aplicacion')['nota'],1,'.','.');
                 $nota_aplicacionid = $tabla->get_nota_corte('aplicacion')['id'];
                 $nota_concepto = ($tabla->get_nota_corte('concepto')['nota']);
                 $nota_conceptoid = $tabla->get_nota_corte('concepto')['id'];
                 $nota_final = number_format($tabla->get_nota_corte('final')['nota'],1,'.','.');
        
            } */
                }
            }
        @endphp
      @include('myforms.components_exp.formulario_editar_notas',[
        "id" => 'myform_update_notas_exp',
      ])
    @endslot
@endcomponent
<!-- /modal -->
