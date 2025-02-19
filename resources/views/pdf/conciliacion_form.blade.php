<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Completo</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"],
        textarea {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <h2>INFORMACIÓN PERSONAL Y DE CONTACTO</h2>
    <table>
        <tr>
            <th>Fecha</th>
            <td><input type="text" placeholder="Haga clic aquí o pulse para escribir una fecha."></td>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td><input type="text" placeholder="NOMBRE DE LA PARTE CONVOCANTE."></td>
        </tr>
        <tr>
            <th>Identificación</th>
            <td>
                <select>
                    <option>Elija tipo de documento.</option>
                </select>
                <input type="text" placeholder="No. Número y Ciudad.">
                <input type="text" placeholder="Fecha nacimiento">
                <select>
                    <option>Pulse para elegir.</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Dirección para notificaciones:</th>
            <td><input type="text" placeholder="Escriba la dirección de domicilio."></td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td><input type="text" placeholder="Teléfono."></td>
        </tr>
        <tr>
            <th>Correo electrónico:</th>
            <td><input type="text" placeholder="Escriba correo electrónico."></td>
        </tr>
    </table>

    <h2>INFORMACIÓN IDENTITARIA Y DE INCLUSIVIDAD</h2>
    <table>
        <tr>
            <th>Nombre identitario</th>
            <td><input type="text" placeholder="Haga clic o pulse aquí para escribir texto."></td>
        </tr>
        <tr>
            <th>Sexo</th>
            <td>
                <input type="radio" name="sexo" value="Hombre"> Hombre
                <input type="radio" name="sexo" value="Mujer"> Mujer
                <input type="radio" name="sexo" value="Intersexual"> Intersexual
                <input type="radio" name="sexo" value="Indeterminado"> Indeterminado
            </td>
        </tr>
        <tr>
            <th>Género</th>
            <td>
                <input type="radio" name="genero" value="Masculino"> Masculino
                <input type="radio" name="genero" value="Femenino"> Femenino
                <input type="radio" name="genero" value="Transgénero"> Transgénero
            </td>
        </tr>
        <tr>
            <th>¿Posee algún tipo de discapacidad?</th>
            <td>
                <input type="radio" name="discapacidad" value="SI"> SI
                <input type="radio" name="discapacidad" value="NO"> NO
            </td>
        </tr>
        <tr>
            <th>¿Requiere algún tipo de apoyo? P.e. Intérprete de lengua de señas, lector de pantalla, etc.</th>
            <td>
                <input type="radio" name="apoyo" value="SI"> SI
                <input type="radio" name="apoyo" value="NO"> NO
                <input type="text" placeholder="¿Cuál? Haga clic o pulse aquí para escribir texto.">
            </td>
        </tr>
        <tr>
            <th>¿Pertenece a algún grupo étnico?</th>
            <td>
                <input type="radio" name="etnico" value="SI"> SI
                <input type="radio" name="etnico" value="NO"> NO
            </td>
        </tr>
        <tr>
            <th>¿Es líder, lideresa, defensor o defensora de DD.HH?</th>
            <td>
                <input type="radio" name="lider" value="SI"> SI
                <input type="radio" name="lider" value="NO"> NO
            </td>
        </tr>
    </table>

    <h2>INFORMACIÓN SOCIOECONÓMICA</h2>
    <table>
        <tr>
            <th>Estado civil:</th>
            <td>
                <input type="radio" name="estado_civil" value="Soltero(a)"> Soltero(a)
                <input type="radio" name="estado_civil" value="Casado(a)"> Casado(a)
                <input type="radio" name="estado_civil" value="Divorciado(a)"> Divorciado(a)
                <input type="radio" name="estado_civil" value="Viudo(a)"> Viudo(a)
                <input type="radio" name="estado_civil" value="UMH (Declarada)"> UMH (Declarada)
                <input type="radio" name="estado_civil" value="Unión libre"> Unión libre
            </td>
        </tr>
        <tr>
            <th>Tipo de vivienda:</th>
            <td>
                <input type="radio" name="vivienda" value="Propia"> Propia
                <input type="radio" name="vivienda" value="Arrendada"> Arrendada
                <input type="radio" name="vivienda" value="Familiar"> Familiar
                <input type="text" placeholder="Estrato 1 2 3 4">
            </td>
        </tr>
        <tr>
            <th>Personas a cargo:</th>
            <td>
                <input type="radio" name="personas_cargo" value="1"> 1
                <input type="radio" name="personas_cargo" value="2"> 2
                <input type="radio" name="personas_cargo" value="3"> 3
                <input type="radio" name="personas_cargo" value="4"> 4
                <input type="radio" name="personas_cargo" value="5+"> 5+
                <input type="radio" name="sisben" value="SI"> Sisbén SI
                <input type="radio" name="sisben" value="NO"> NO
                <select>
                    <option>Elija un elemento.</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Escolaridad:</th>
            <td>
                <select>
                    <option>Elija un elemento.</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Ingreso mensual:</th>
            <td>
                <input type="text" placeholder="$ Pulse aquí para escribir texto.">
                <input type="text" placeholder="Egresos/Gastos mensuales $ Pulse aquí para escribir texto.">
            </td>
        </tr>
    </table>

    <h2>INFORMACIÓN APODERADO (A)</h2>
    <table>
        <tr>
            <th>Nombre:</th>
            <td><input type="text" placeholder="NOMBRE DEL APODERADO."></td>
        </tr>
        <tr>
            <th>Identificación:</th>
            <td>
                <select>
                    <option>Elija tipo de documento.</option>
                </select>
                <input type="text" placeholder="No. Identificación.">
            </td>
        </tr>
        <tr>
            <th>Tarjeta Profesional No.:</th>
            <td><input type="text" placeholder="No. De tarjeta profesional."></td>
        </tr>
        <tr>
            <th>Teléfono:</th>
            <td><input type="text" placeholder="Teléfono."></td>
        </tr>
        <tr>
            <th>Dirección para notificaciones:</th>
            <td><input type="text" placeholder="Escriba la dirección de domicilio."></td>
        </tr>
        <tr>
            <th>Correo electrónico:</th>
            <td><input type="text" placeholder="Escriba correo electrónico para notificaciones."></td>
        </tr>
    </table>

    <h2>INFORMACIÓN DEL ASUNTO</h2>
    <table>
        <tr>
            <th>Cuantía:</th>
            <td><input type="text" placeholder="$ Valor."></td>
        </tr>
        <tr>
            <th>No. Convocados:</th>
            <td>
                <input type="radio" name="convocados" value="1"> 1
                <input type="radio" name="convocados" value="2"> 2
                <input type="radio" name="convocados" value="3"> 3
                <input type="radio" name="convocados" value="4"> 4
                <input type="radio" name="convocados" value="5+"> 5+
                <select>
                    <option>Inicio del conflicto Elija un elemento.</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Finalidad de adquisición del servicio</th>
            <td>
                <input type="radio" name="finalidad" value="Resolver de manera alternativa el conflicto"> Resolver
                de manera alternativa el conflicto
                <input type="radio" name="finalidad" value="Cumplir requisito de procedibilidad"> Cumplir requisito
                de procedibilidad
            </td>
        </tr>
        <tr>
            <th>Modalidad de la audiencia de conciliación</th>
            <td>
                <input type="radio" name="modalidad" value="Virtual"> Virtual
                <input type="radio" name="modalidad" value="Presencial"> Presencial
            </td>
        </tr>
    </table>

    <h2>PARTE CONVOCADA</h2>
    <table>
        <tr>
            <th>Nombre:</th>
            <td><input type="text" placeholder="NOMBRE DE LA PARTE CONVOCADA."></td>
        </tr>
        <tr>
            <th>Tipo de persona</th>
            <td>
                <select>
                    <option>Elija un elemento.</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Identificación</th>
            <td>
                <select>
                    <option>Elija tipo de documento.</option>
                </select>
                <input type="text" placeholder="No. Número y Ciudad.">
            </td>
        </tr>
        <tr>
            <th>Dirección para notificaciones</th>
            <td><input type="text" placeholder="Escriba la dirección de domicilio."></td>
        </tr>
        <tr>
            <th>Celular</th>
            <td><input type="text" placeholder="Celular."></td>
        </tr>
        <tr>
            <th>Correo electrónico:</th>
            <td><input type="text" placeholder="Escriba correo electrónico."></td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td><input type="text" placeholder="Teléfono fijo."></td>
        </tr>
    </table>

    <h2>REPRESENTACIÓN LEGAL (Solo para personas jurídicas)</h2>
    <table>
        <tr>
            <th>Nombre:</th>
            <td><input type="text" placeholder="NOMBRE DEL REPRESENTANTE LEGAL DE LA PERSONA JURÍDICA."></td>
        </tr>
        <tr>
            <th>Identificación:</th>
            <td>
                <select>
                    <option>Elija tipo de documento.</option>
                </select>
                <input type="text" placeholder="No. Identificación.">
            </td>
        </tr>
    </table>

    <h2>ASUNTO A CONCILIAR</h2>
    <table>
        <tr>
            <th>HECHOS (No diligenciar si se encuentran en petición adjunta)</th>
            <td>
                <textarea rows="4" placeholder="Describa los hechos aquí."></textarea>
            </td>
        </tr>
        <tr>
            <th>PRETENSIONES (No diligenciar si se encuentran en petición adjunta)</th>
            <td>
                <textarea rows="4" placeholder="Describa las pretensiones aquí."></textarea>
            </td>
        </tr>
        <tr>
            <th>ANEXOS (No diligenciar si se encuentran en petición adjunta)</th>
            <td>
                <textarea rows="4" placeholder="Describa los anexos aquí."></textarea>
            </td>
        </tr>
    </table>

</body>

</html>
