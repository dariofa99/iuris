<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Completo</title>
    <style>
        @page {
            margin: 160px 50px 50px 50px;
            /* Espacio para el encabezado */
        }

        header,
        img {
            width: 100%;
        }

        header {

            position: fixed;
            top: -145px;
            /* Ajusta según sea necesario */
            left: 0;
            right: 0;
            height: 100px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid rgb(0, 0, 0);
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    @php
        $parte_ = $conciliacion->getUser(205); //Solicitante
    @endphp

    <header>
        <img src="{{ public_path('dist/img/headersolconc.png') }}" alt="">
    </header>

    <table class="table" border="1">
        <tr>
            <th>Fecha</th>
            <td colspan="5">
                <label>
                    {{ getLongDateWithHour($conciliacion->created_at) }}
                </label>

            </td>
        </tr>
        <tr>
            <td colspan="6" align="center">
                <h4 style="display: block;text-align:center">
                    INFORMACIÓN PERSONAL Y DE CONTACTO
                </h4>
            </td>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td colspan="5">{{ $parte_->name }} {{ $parte_->lastname }}</td>
        </tr>
        <tr>
            <th>Identificación</th>
            <td>
                {{ $parte_->tipo_doc->ref_nombre }}

                {{-- de  {{ $parte_->getStaticDataValByShortName('lugar_exp._documento', 'datos_personales')->value ?? 'Sin datos' }} --}}
            </td>

            <th>No</th>
            <td>
                {{ $parte_->idnumber }}
            </td>

            <th>Fecha de nacimiento</th>
            <td>
                {{ getSmallDate($parte_->fechanacimien) }}
            </td>
        </tr>
        <tr>
            <th>Dirección para notificaciones:</th>
            <td colspan="3">
                {{ $parte_->address }}
            </td>

            <th>Celular</th>
            <td>{{ $parte_->tel1 }} - {{ $parte_->tel2 }}</td>
        </tr>
        <tr>
            <th>Correo electrónico:</th>
            <td colspan="5">
                {{ $parte_->email }}
            </td>
        </tr>
    </table>

    <h2>INFORMACIÓN IDENTITARIA Y DE INCLUSIVIDAD</h2>
    <table>
        <td>Sexo</td>
        <td>
            {{ $parte_->genero->ref_nombre }}
        </td>
        @include('pdf.conciliacion_form_adquestion', [
            'data' => getReferencesDataBySection('enfoque_diferencial', 'users'),
            'discaform' => 'discaform',
            'user' => $parte_,
        ])
        <td>¿Posee algún tipo de discapacidad?</td>
        <td>
            SI
            <input type="radio" @if ($parte_->pbepersondiscap) checked @endif name="discapacidad" value="SI">
            NO
            <input type="radio" @if (!$parte_->pbepersondiscap) checked @endif name="discapacidad" value="NO">
        </td>
        @if ($parte_->pbepersondiscap)
            @include('pdf.conciliacion_form_adquestion', [
                'data' => getReferencesDataBySection('discapacidad', 'users'),
                'discaform' => 'discaform',
                'user' => $parte_,
            ])
        @endif
    </table>

    <h2>INFORMACIÓN SOCIOECONÓMICA</h2>
    <table>
        <tr>
            <th>Estado civil:</th>
            <td>
                {{ $parte_->estado_civil->ref_nombre }}
            </td>
        </tr>
        @include('pdf.conciliacion_form_adquestion', [
            'data' => getReferencesDataBySection('socio_economica', 'users'),
            'discaform' => 'discaform',
            'user' => $parte_,
        ])
    </table>
    @php
        $parte_ = $conciliacion->getUser(196); //Apoderado
    @endphp
    <h2>INFORMACIÓN APODERADO (A)</h2>
    <table>

        @if ($parte_->idnumber != null)
            <tr>
                <th>Nombre:</th>
                <td colspan="5">{{ $parte_->name }} {{ $parte_->lastname }}</td>
            </tr>
            <tr>
                <th>Identificación</th>
                <td>
                    {{ $parte_->tipo_doc->ref_nombre }} de

                    {{ $parte_->getStaticDataValByShortName('lugar_exp._documento', 'datos_personales')->value ?? 'Sin datos' }}
                </td>

                <th>No</th>
                <td colspan="3">
                    {{ $parte_->idnumber }}
                </td>


            </tr>
            <tr>
                <th>Dirección para notificaciones:</th>
                <td>
                    {{ $parte_->address }}
                </td>

                <th>Celular</th>
                <td colspan="3">{{ $parte_->tel1 }} - {{ $parte_->tel2 }}</td>
            </tr>
            <tr>
                <th>Correo electrónico:</th>
                <td colspan="5">
                    {{ $parte_->email }}
                </td>
            </tr>

            <tr>
                <th>Tarjeta Profesional No:</th>
                <td colspan="5">
                    {{ $parte_->codigo_estudiantil }}
                </td>
            </tr>
        @else
            <tr>
                <th colspan="2">
                    Sin registro
                </th>
            </tr>
        @endif
    </table>

    <h2>INFORMACIÓN DEL ASUNTO</h2>
    <table>
        @include('pdf.conciliacion_form_adquestion', [
            'data' => getReferencesDataBySection('asunto', 'conciliaciones'),
            'user' => $conciliacion,
        ])
    </table>

    <h2>INFORMACIÓN DE PARTE CONVOCADA</h2>
    <table>
        @foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $parte_)
            <tr>

                <th colspan="6" style="text-align: center !important;">
                    Parte convocada {{ $key + 1 }}

                </th>



            </tr>
            @if ($parte_->idnumber != null)
                <tr>
                    <th>Nombre:</th>
                    <td colspan="3">{{ $parte_->name }} {{ $parte_->lastname }}</td>

                    <th>Tipo de persona</th>
                    <td>
                        {{ $parte_->tipo_persona->ref_nombre }}
                    </td>

                </tr>
                <tr>

                    <th>Identificación</th>
                    <td>
                        {{ $parte_->tipo_doc->ref_nombre }} de

                        {{ $parte_->getStaticDataValByShortName('lugar_exp._documento', 'datos_personales')->value ?? 'Sin datos' }}
                    </td>



                    <th>No</th>
                    <td colspan="3">
                        {{ $parte_->idnumber }}
                    </td>


                </tr>
                <tr>
                    <th>Dirección para notificaciones:</th>
                    <td colspan="3">
                        {{ $parte_->address }}
                    </td>

                    <th>Celular</th>
                    <td>{{ $parte_->tel1 }} - {{ $parte_->tel2 }}</td>
                </tr>
                <tr>
                    <th>Correo electrónico:</th>
                    <td colspan="5">
                        {{ $parte_->email }}
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td colspan="2">
                                    <h2>INFORMACIÓN IDENTITARIA Y DE INCLUSIVIDAD</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>Sexo</td>
                                <td>
                                    {{ $parte_->genero->ref_nombre }}
                                </td>
                            </tr>
                            @include('pdf.conciliacion_form_adquestion', [
                                'data' => getReferencesDataBySection('enfoque_diferencial', 'users'),
                                'discaform' => 'discaform',
                                'user' => $parte_,
                            ])
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="6">
                        <table>
                            <tr>
                                <td>
                                    ¿Posee algún tipo de discapacidad?
                                </td>
                                <td>
                                    SI
                                    <input type="radio" @if ($parte_->pbepersondiscap) checked @endif
                                        name="discapacidad" value="SI">
                                    NO
                                    <input type="radio" @if (!$parte_->pbepersondiscap) checked @endif
                                        name="discapacidad" value="NO">
                                </td>
                            </tr>
                            @if ($parte_->pbepersondiscap)
                                @include('pdf.conciliacion_form_adquestion', [
                                    'data' => getReferencesDataBySection('discapacidad', 'users'),
                                    'discaform' => 'discaform',
                                    'user' => $parte_,
                                ])
                            @endif

                        </table>
                    </td>

                </tr>
            @else
                <tr>
                    <th colspan="2">
                        Sin registro
                    </th>
                </tr>
            @endif
        @endforeach

    </table>

    <h2>REPRESENTACIÓN LEGAL (Solo para personas jurídicas)</h2>
    <table>
        @foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $personajuridica)
            @if ($personajuridica->tipo_persona->id == 238)
                <tr>
                    <th colspan="6" style="text-align: center !important;">
                        Rep. legal de parte convocada {{ $personajuridica->name }} {{ $personajuridica->lastname }}
                    </th>
                </tr>
                @forelse ($personajuridica->conc_rep_legal as $key_ => $conciliacion_rep_legal)
                    @php
                        $parte_ = $conciliacion->getUserByFilter([
                            'tipo_usuario_id' => 198,
                            'user_id' => $conciliacion_rep_legal->pivot->user_replegal_id,
                        ]);
                    @endphp

                    @if ($parte_->idnumber != null)
                        <tr>
                            <th colspan="6" style="text-align: center !important;">
                                Rep. legal {{ $key_ + 1 }}
                            </th>
                        </tr>

                        <tr>
                            <th>Nombre:</th>
                            <td colspan="3">{{ $parte_->name }} {{ $parte_->lastname }}</td>
                            <th>Tipo de persona</th>
                            <td>
                                {{ $parte_->tipo_persona->ref_nombre }}
                            </td>
                        </tr>
                        <tr>

                            <th>Identificación</th>
                            <td>
                                {{ $parte_->tipo_doc->ref_nombre }} de

                                {{ $parte_->getStaticDataValByShortName('lugar_exp._documento', 'datos_personales')->value ?? 'Sin datos' }}
                            </td>



                            <th>No</th>
                            <td colspan="3">
                                {{ $parte_->idnumber }}
                            </td>


                        </tr>
                        <tr>
                            <th>Dirección para notificaciones:</th>
                            <td colspan="3">
                                {{ $parte_->address }}
                            </td>

                            <th>Celular</th>
                            <td>{{ $parte_->tel1 }} - {{ $parte_->tel2 }}</td>
                        </tr>
                        <tr>
                            <th>Correo electrónico:</th>
                            <td colspan="5">
                                {{ $parte_->email }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <h2>INFORMACIÓN IDENTITARIA Y DE INCLUSIVIDAD</h2>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <table>

                                    <tr>
                                        <td>Sexo</td>
                                        <td>
                                            {{ $parte_->genero->ref_nombre }}
                                        </td>
                                    </tr>
                                    @include('pdf.conciliacion_form_adquestion', [
                                        'data' => getReferencesDataBySection('enfoque_diferencial', 'users'),
                                        'discaform' => 'discaform',
                                        'user' => $parte_,
                                    ])
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <table>
                                    <tr>
                                        <td>
                                            ¿Posee algún tipo de discapacidad?
                                        </td>
                                        <td>
                                            SI
                                            <input type="radio" @if ($parte_->pbepersondiscap) checked @endif
                                                name="discapacidad" value="SI">
                                            NO
                                            <input type="radio" @if (!$parte_->pbepersondiscap) checked @endif
                                                name="discapacidad" value="NO">
                                        </td>
                                    </tr>
                                    @if ($parte_->pbepersondiscap)
                                        @include('pdf.conciliacion_form_adquestion', [
                                            'data' => getReferencesDataBySection('discapacidad', 'users'),
                                            'discaform' => 'discaform',
                                            'user' => $parte_,
                                        ])
                                    @endif

                                </table>
                            </td>

                        </tr>
                    @else
                        <tr>
                            <th colspan="2">
                                Sin registro
                            </th>
                        </tr>
                    @endif

                @empty
                    Todavia no hay usuarios
                @endforelse
            @endif
        @endforeach
    </table>

    <h2>ASUNTO A CONCILIAR</h2>
    <table>
        <tr>
            <th colspan="3">HECHOS</th>
        </tr>
        @foreach ($conciliacion->hechos_pretensiones()->where('tipo_id', 206)->get() as $key => $hecho)
            <tr>
                <td colspan="3">
                    {{ $hecho->descripcion }}
                </td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">PRETENSIONES</th>

        </tr>
        @foreach ($conciliacion->hechos_pretensiones()->where('tipo_id', 207)->get() as $key => $hecho)
            <tr>
                <td colspan="3">
                    {{ $hecho->descripcion }}
                </td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">ANEXOS</th>
        </tr>
        @foreach ($conciliacion->files as $key => $file)
            <tr class="files" data-type="{{ $file->pivot->category_id }}">
                <td colspan="2">
                    {{ $file->pivot->concepto }}
                </td>
                <td width="4%">
                    <a rel="noopener noreferrer" target="_blank"
                        href="/conciliaciones/download/file/{{ $file->pivot->file_id }}">
                        Descargar
                    </a>
                </td>
            </tr>
        @endforeach
    </table>

</body>

</html>
