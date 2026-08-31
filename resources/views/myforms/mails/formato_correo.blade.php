@extends('myforms.mails.layout.dashboard')

@section('area_content')

    {{-- =====================================================
         CONTENIDO PRINCIPAL
    ====================================================== --}}

    <table
        role="presentation"
        cellpadding="0"
        cellspacing="0"
        width="100%"
        border="0"
        style="font-family:'Lato',Arial,sans-serif;"
    >

        <tbody>

            {{-- =================================================
                 ENCABEZADO
            ================================================== --}}

            <tr>

                <td
                    align="left"
                    style="
                        padding:28px 40px 20px;
                        border-bottom:1px solid #edf1f2;
                    "
                >

                    <table
                        role="presentation"
                        cellpadding="0"
                        cellspacing="0"
                        border="0"
                    >

                        <tr>

                            <td
                                width="38"
                                height="38"
                                align="center"
                                valign="middle"
                                style="
                                    width:38px;
                                    height:38px;
                                    background-color:#eaf5f6;
                                    border-radius:7px;
                                    color:#006975;
                                    font-size:17px;
                                "
                            >

                                &#9878;

                            </td>


                            <td style="padding-left:10px;">

                                <div
                                    style="
                                        font-size:17px;
                                        line-height:22px;
                                        font-weight:600;
                                        color:#34474b;
                                    "
                                >

                                    IURIS

                                </div>

                                <div
                                    style="
                                        font-size:11px;
                                        line-height:16px;
                                        color:#89999d;
                                    "
                                >

                                    Sistema de gestión jurídica

                                </div>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>


            {{-- =================================================
                 MENSAJE
            ================================================== --}}

            <tr>

                <td
                    align="left"
                    style="
                        padding:30px 40px 15px;
                        font-family:'Lato',Arial,sans-serif;
                    "
                >

                    <div
                        style="
                            font-size:13px;
                            line-height:21px;
                            color:#526469;
                        "
                    >

                        {!! $mensaje !!}

                    </div>

                </td>

            </tr>


            {{-- =================================================
                 REMITENTE
            ================================================== --}}

            @if(isset($user_created))

                <tr>

                    <td
                        style="
                            padding:5px 40px 25px;
                        "
                    >

                        <table
                            role="presentation"
                            cellpadding="0"
                            cellspacing="0"
                            width="100%"
                            border="0"
                            style="
                                background-color:#f7fafb;
                                border-left:3px solid #006975;
                            "
                        >

                            <tr>

                                <td
                                    style="
                                        padding:10px 13px;
                                    "
                                >

                                    <div
                                        style="
                                            font-size:10px;
                                            line-height:15px;
                                            color:#89999d;
                                            text-transform:uppercase;
                                        "
                                    >

                                        Enviado por

                                    </div>


                                    <div
                                        style="
                                            font-size:12px;
                                            line-height:18px;
                                            color:#34474b;
                                            font-weight:600;
                                        "
                                    >

                                        {!! $user_created !!}

                                    </div>

                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

            @endif


            {{-- =================================================
                 BOTÓN
            ================================================== --}}

            @if (isset($url) && $url != null)

                <tr>

                    <td
                        align="left"
                        style="
                            padding:0 40px 35px;
                        "
                    >

                        <table
                            role="presentation"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                        >

                            <tr>

                                <td
                                    align="center"
                                    bgcolor="#006975"
                                    style="
                                        border-radius:5px;
                                    "
                                >

                                    <!--[if mso]>
                                    <v:roundrect
                                        xmlns:v="urn:schemas-microsoft-com:vml"
                                        xmlns:w="urn:schemas-microsoft-com:office:word"
                                        href="{{ $url }}"
                                        style="
                                            height:42px;
                                            v-text-anchor:middle;
                                            width:190px;
                                        "
                                        arcsize="10%"
                                        stroke="f"
                                        fillcolor="#006975"
                                    >
                                    <w:anchorlock/>

                                    <center
                                        style="
                                            color:#FFFFFF;
                                            font-family:Arial,sans-serif;
                                            font-size:13px;
                                            font-weight:bold;
                                        "
                                    >
                                    <![endif]-->


                                    <a
                                        href="{{ $url }}"
                                        target="_blank"
                                        style="
                                            display:inline-block;
                                            padding:12px 22px;
                                            font-family:'Lato',Arial,sans-serif;
                                            font-size:13px;
                                            line-height:18px;
                                            font-weight:600;
                                            color:#FFFFFF;
                                            text-decoration:none;
                                            background-color:#006975;
                                            border-radius:5px;
                                        "
                                    >

                                        <span style="vertical-align:middle;">

                                            @if(isset($buttonMessage))

                                                {{ $buttonMessage }}

                                            @else

                                                Ir al caso

                                            @endif

                                        </span>

                                        <span
                                            style="
                                                padding-left:7px;
                                                font-size:14px;
                                            "
                                        >

                                            &#8594;

                                        </span>

                                    </a>


                                    <!--[if mso]>
                                    </center>
                                    </v:roundrect>
                                    <![endif]-->

                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

            @endif


        </tbody>

    </table>

@stop