@extends('myforms.mails.layout.dashboard')


@section('area_content')

    <div style="font-family:'Lato',sans-serif; padding: 40px 40px 30px; text-align: center;">
        <p style="font-size: 20px; line-height: 28px; color: #333333; font-weight: bold; margin: 0 0 30px 0;">
            Código único de recuperación de cuenta
        </p>

         <p>
                Se ha generado un código de seguridad temporal para recuperar su cuenta. 
            </p>


        <div style="background-color: #F9FBE7; padding: 30px 20px; border-radius: 10px; border: 1px solid #E0E0E0; margin: 30px 0;">
            @php
                $token = str_pad($user->confirm_token, 6, '0', STR_PAD_LEFT);
                $digits = str_split($token);
            @endphp

           
            <table role="presentation" cellpadding="0" cellspacing="10" border="0" style="margin: 0 auto;">
                <tr>
                    @foreach ($digits as $digit)
                        <td style="padding: 0;">
                            <div style="width: 50px; height: 50px; border: 2px solid #2E7D32; border-radius: 8px; background: linear-gradient(135deg, #E8F5E9 0%, #F1F8E9 100%); font-size: 28px; font-weight: bold; color: #1B5E20; line-height: 50px; text-align: center; box-shadow: 0 2px 8px rgba(46, 125, 50, 0.15); margin: 0 1px;">
                                {{ $digit }}
                            </div>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>

        <p style="font-size: 14px; line-height: 20px; color: #666666; margin: 20px 0;">
            Si tiene problemas para visualizar los 6 dígitos, su código es: <strong>{{ $user->confirm_token }}</strong>
        </p>

        <p style="font-size: 13px; line-height: 20px; color: #888888; margin: 10px 0;">
            <strong>Válido por 10 minutos.</strong> Por seguridad institucional, no comparta este código.
        </p>
    </div>

    <div style="font-family:'Lato',sans-serif; padding: 40px 40px 30px; text-align: center; color: #888888; font-size: 12px;">
        <p style="margin: 0;">
            Ignore este correo electrónico si no solicitó un cambio de contraseña.
        </p>
    </div>
@stop
