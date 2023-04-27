<div>
    <table class="table">
        <tbody>
            <tr>
                <th>NOMBRES:</th>
                <td>{{$user->name}} {{$user->lastname}}</td>
            </tr>           
            <tr>
                <th>IDENTIFICACIÓN:</th>
                <td>{{$user->idnumber}}
                </td>
            </tr>
            <tr>
                <th>TELÉFONO:</th>
                <td> {{$user->tel1}} </td>
            </tr>

            <tr>
                <th>DIRECCIÓN:</th>
                <td>    {{$user->address}}       </td>
            </tr>          
         
            <tr>

                <td colspan="2" style="text-align: center;">
                    <img src="{{asset("thumbnails/".$user->image)}}"
                        style="border-radius: 10px;-webkit-box-shadow: -1px 10px 9px 0px rgba(0,0,0,0.75);-moz-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75); width: 180px;"
                        alt="User Image">
                </td>
            </tr>         
        </tbody>
    </table>
    <div id="content_aditional_data">    
        @include('myforms.conciliaciones.componentes.aditional_user_detalles_data',[
            'section'=> Request::has('section') ? Request::get('section') : 'solicitante'
        ]
        ) 
   </div>
</div>