<table style="font-family:arial;font-size:13.333px;width:100%">
    <tr>
        <td align="center" style="border:1px solid black">
            <img src="{{ public_path('/img/logoudenar_2.png') }}" width="100" height="100" />
        </td>

        </td>
        <td align="center" style="border:1px solid black;font-style:bold">
            CONSULTORIOS JURÍDICOS - CENTRO DE CONCILIACIÓN “EDUARDO ALVARADO HURTADO”<br>
            <br>
            <br>
            AUTORIZACIÓN ESTUDIANTIL

        </td>
        <td style="border:1px solid black">
            <span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Código:
                CJU-PRS-FR-04</span>
            <span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Página: 1 de 1</span>
            <span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Versión: 4</span>
            <span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Vigente a Partir de:
                2025-07-09
            </span>

        </td>
    </tr>

</table>
<br>
<div style="padding:0px 50px 0px 50px">


    <table>
        <tr>
            <td align="center" style="font-family:arial;font-size:18.667px">
                <b>LA DIRECCIÓN ADMINISTRATIVA DE CONSULTORIOS JURÍDICOS Y CENTRO DE CONCILIACIÓN DE LA
                    UNIVERSIDAD DE NARIÑO

                    <br><br><br>
                    <span style="font-family:arial;font-size:16px">AUTORIZA:</span>
                </b>
                <br><br>
            </td>
        </tr>
        <tr>
            <td align="justify" style="font-family:arial;font-size:16px">
                <p>
                    @if ($autorizacion->genero == 'f')
                        A la estudiante
                    @else
                        Al estudiante
                    @endif
                    {{ $autorizacion->nombre_estudiante }}, quien se identifica con
                    Cédula de Ciudadanía Nro. {{ $autorizacion->num_identificacion }} expedida en
                    {{ $autorizacion->doc_expedicion }}, y carné estudiantil Nro. {{ $autorizacion->num_carne }}
                    y que se encuentra @if ($autorizacion->genero == 'f')
                        registrada
                    @else
                        registrado
                    @endif en Consultorios Jurídicos de la Universidad
                    de Nariño, aprobado mediante Resolución
                    Nro. 1808 de 3 de Octubre de 1991 por el Honorable Tribunal Superior del Distrito
                    Judicial de Pasto, para que actúe en calidad de
                    {{ $autorizacion->calidad_de }} dentro del proceso No. {{ $autorizacion->num_radicado }}, que cursa ante el/la
                    {{ $autorizacion->juzgado }}.
                </p>
                <p>
                    Esta autorizacion se expide en San Juan de Pasto, a los
                    {{ getLettersDays($autorizacion->fecha_autorizado) }},
                    {{ parseLongDate($autorizacion->fecha_autorizado) }} para efectos de que trata el articulo 2º de la Ley 2113 de 2021.
                </p>
                <p>
                    <b>Nota: </b>Favor presentar copia del Acta de Posesión ante la Dirección Administrativa de Consultorios
                     Jurídicos y Centro de Conciliación de la Universidad de Nariño, en caso de procesos judiciales ante 
                     la entidad correspondiente. Presentar copia de esta autorización con el sello de recibido de la entidad correspondiente.
                </p>
            </td>
        </tr>

    </table>
    <br><br><br><br>
    <table style="width:100%">
        <tr>
            <td width="5%">
                Firma
            </td>
        
         
            <td align="center" width="45%">
                <img src="{{ public_path('/img/firmadiradmin.jpeg') }}" style="position: absolute; background-color: white;margin-top:-35;" width="160"
                    height="105" />
            </td>
            <td width="5%"></td>
              <td width="5%">
                Firma
            </td>
        
         
            <td align="center" width="0%">
                
            </td>

        </tr>

     <tr>

            <td colspan="2" width="48%" >
                <span style="border-bottom: 1px solid black;display: block;">
                    </span>
                
              <span align="center" style="display: block; text-align: center;"> DIRECCIÓN ADMINISTRATIVA </span>
            </td>
            <td width="4%"></td>
            
            <td colspan="2">
                 <span style="border-bottom: 1px solid black;display:block;height: 20px;"></span>
                Nombre:<br>
               <span align="center" style="display: block; text-align: center;"> ESTUDIANTE </span>

            </td>
        </tr>

    {{--
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <p><b style="font-family:arial;font-size:13.333px">VIGILADO Ministerio de Justicia y del Derecho.</b>
                </p>
            </td>
        </tr> --}}
    </table>
    <table style="margin-top:100px">
        <tr>
            <td colspan="2"> <span style="font-family:arial;font-size:11px">Para verificar la autenticidad del
                    presente documento consulte el siguiente enlace <b>{{ url('/autorizacion') }}</b></span></td>
        </tr>
    </table>
</div>
