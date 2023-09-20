@extends('myforms.mails.layout.dashboard')

@section('area_content')

    <table style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%"
        border="0">
        <tbody>
            <tr>
                <td style="overflow-wrap:break-word;word-break:break-word;padding:40px 40px 30px;font-family:'Lato',sans-serif;"
                    align="left">

                    <div style="font-size: 14px; line-height: 140%; text-align: left; word-wrap: break-word;">
                        <p style="font-size: 14px; line-height: 140%;"><span
                                style="font-size: 18px; line-height: 25.2px; color: #666666;">Hola,</span>
                        </p>
                      
                        <p style="font-size: 14px; line-height: 140%;">&nbsp;
                        </p>
                        <p style="font-size: 14px; line-height: 140%;"><span
                                style="font-size: 18px; line-height: 25.2px; color: #666666;">

                               {!!  $mensaje !!} </span></p>
                    </div>

                </td>
            </tr>
        </tbody>
    </table>

    @if (isset($url))
        <table style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%"
            border="0">
            <tbody>
                <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:0px 40px;font-family:'Lato',sans-serif;"
                        align="left">
                        <p style="font-size: 14px; line-height: 140%;"><span
                                style="font-size: 18px; line-height: 25.2px; color: #666666;">

                                Para ingresar al caso de click en el botón: </span></p>
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:10px;overflow-wrap:break-word;word-break:break-word;padding:0px 40px;font-family:'Lato',sans-serif;"
                        align="left">

                        <!--[if mso]><style>.v-button {background: transparent !important;}</style><![endif]-->
                        <div align="left">
                            <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:51px; v-text-anchor:middle; width:205px;" arcsize="2%"  stroke="f" fillcolor="#0e8e4c"><w:anchorlock/><center style="color:#FFFFFF;font-family:'Lato',sans-serif;"><![endif]-->
                            <a href="{{ $url }}" target="_blank" class="v-button"
                                style="box-sizing: border-box;display: inline-block;font-family:'Lato',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #0e8e4c; border-radius: 1px;-webkit-border-radius: 1px; -moz-border-radius: 1px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;font-size: 14px;">
                                <span style="display:block;padding:15px 40px;line-height:120%;"><span
                                        style="font-size: 18px; line-height: 21.6px;">
                                        Ir al caso
                                    </span></span>
                            </a>
                            <!--[if mso]></center></v:roundrect><![endif]-->
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>
    @endif
    @stop
