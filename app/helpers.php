


<?php


use Carbon\Carbon;
use App\ReferencesData;



if (!function_exists('currentUser')) {
    function currentUser()
    {
        return auth()->user();
    }
}
if (!function_exists('currentUserInConciliacion')) {
    function currentUserInConciliacion($conciliacion, $roles)
    {
        $role = auth()->user()->tipo_conciliacion()->where('conciliacion_id', $conciliacion)->get();
        // dd($role);
        if (is_array($roles)) {
            foreach ($role as $key => $rol) {

                if (in_array(strtolower($rol->ref_value), $roles)) {
                    return true;
                }
            }
        }
        return  false;
    }
}


function getPercent($total, $part)
{
    if(is_numeric($total) && is_numeric($part)){
        return ($part * 100) / $total;
    }
    return 0;
} 

function getColorByPercent($percent)
{
    if ($percent >= 0 && $percent <= 40) {
        return 'success';
    } elseif ($percent > 40 && $percent <= 70) {
        return 'warning';
    } elseif ($percent > 70 && $percent <= 100) {
        return 'danger';
    }
}

function fechasSem($criterio)
{

    $date = Carbon::now();


    if ($criterio == 'fechaIni') {
        $fecha = $date->subDays(30)->format('Y-m-d');
    } elseif ($criterio == 'fechaFin') {

        $fecha = $date->format('Y-m-d');
    }

    return $fecha;
}


function TiempoTrans($criterio)
{

    Carbon::setLocale('es');
    $date = Carbon::parse($criterio);
    $fecha = $date->diffForHumans();

    return $fecha;
}
function getSmallDate($date)
{
    $created_at = Carbon::parse($date);

    $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $fecha = $created_at->day . ' ' . $meses[($created_at->month) - 1] . ". " . $created_at->year;

    return $fecha;
}

function getSmallDateWithHour($date)
{
    $created_at = Carbon::parse($date);

    $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $fecha = $created_at->day . ' ' . $meses[($created_at->month) - 1] . ". " . $created_at->year . ". " . $created_at->format('g:i A');

    return $fecha;
}


function getLongDateWithHour($date)
{
    $created_at = Carbon::parse($date);

    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $fecha = $created_at->day . ' ' . $meses[($created_at->month) - 1] . " del " . $created_at->year . " a las " . $created_at->format('g:i A');

    return $fecha;
}

function getLongDate($date)
{
    $created_at = Carbon::parse($date);

    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $fecha = $created_at->day . ' ' . $meses[($created_at->month) - 1] . " del " . $created_at->year;

    return $fecha;
}

function getMonthAndYear($date)
{
    $created_at = Carbon::parse($date);

    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $fecha = $meses[($created_at->month) - 1] . " de " . $created_at->year;

    return $fecha;
}

function getMessagesForPro($estado, $expid)
{
    $messages = [
        243 =>  "Esta recibiendo este correo porque se presentó el <b>autoadmisorio</b> del proceso jurídico asignado en el 
        expediente No. " . $expid . " en " . config("app.name") . ".",
        244 =>  "Esta recibiendo este correo porque se presentó el <b>autoinadmisorio</b> del proceso jurídico asignado en el 
        expediente No. " . $expid . " en " . config("app.name") . ".",
        245 =>  "Esta recibiendo este correo porque se <b>habilitó como proceso jurídico</b> el 
        expediente No. " . $expid . " en " . config("app.name") . ".",
        246 =>  "Esta recibiendo este correo porque se <b>presentó la demanda</b> del proceso jurídico asignado en el 
        expediente No. " . $expid . " en " . config("app.name") . ".",
        247 =>  "Esta recibiendo este correo porque se <b>rechazó la demanda</b> del proceso jurídico asignado en el 
        expediente No. " . $expid . " en " . config("app.name") . ".",
        001 =>  "Esta recibiendo este correo porque se <b>presentó la subsanación</b> de demanda del proceso jurídico asignado en el 
        expediente No. " . $expid . " en " . config("app.name") . ".",
    ];

    return isset($messages[$estado]) ? $messages[$estado] : "Estado no encontrado";
}

function fechaActual()
{



    $date = Carbon::now();
    $fecha = $date->format('Y-m-d');

    return $fecha;
}


function parseLongDate($fecha)
{
    if ($fecha !== null) {
        $date = Carbon::parse($fecha);
        //$fecha = $date->format('Y-m-d');  
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $dia = $date->day;
        if ($date->day < 10) {
            $dia = '0' . $date->day;
        }

        $fecha = $dia . ', días del mes de ' . $meses[$date->month - 1] . ' del año ' . $date->year;

        return $fecha;
    }
    return 'sin fecha';
}

function getLettersDays($fecha)
{

    if ($fecha !== null) {
        $date = Carbon::parse($fecha);
        $dia = $date->day;

        //   return $fecha;


        $dias = [

            0 => '',

            1 => 'un',

            2 => 'dos',

            3 => 'tres',

            4 => 'cuatro',

            5 => 'cinco',

            6 => 'seis',

            7 => 'siete',

            8 => 'ocho',

            9 => 'nueve',

            10 => 'diez',

            11 => 'once',

            12 => 'doce',

            13 => 'trece',

            14 => 'catorce',

            15 => 'quince',

            16 => 'dieciseis',

            17 => 'diecisiete',

            18 => 'dieciocho',

            19 => 'diecinueve',

            20 => 'veinte',
            21 => 'veintiuno',
            22 => 'veintidos',
            23 => 'veintitres',
            24 => 'veinticuatro',
            25 => 'veinticinco',
            26 => 'veintiseis',
            27 => 'veintisiete',
            28 => 'veintiocho',
            29 => 'veintinueve',
            30 => 'treinta',
            31 => 'treintaiuno'

        ];

        return $dias[$dia];
    }
    return 'sin fecha';
}


function fechaFortatoPer($criterio)
{
    Carbon::setLocale('es');
    $date = Carbon::now();
    $fecha = $date->format($criterio);

    return $fecha;
}



function idAleatorio($criterio)
{

    return $criterio;
}




function FullName($criterio1, $criterio2)
{
    return $criterio1 . " " . $criterio2;
}



function active($url)
{
    // dd(($url));
    return $url === request()->is($url);
}





if (!function_exists('icon_link_to_route')) {
    /**
     * Create link to named route with glyphicon icon.
     * 
     * @param  string $icon
     * @param  string $route
     * @param  string $title
     * @param  array  $parameters
     * @param  array  $attributes
     * @return string
     */
    function icon_link_to_route($icon, $route, $title = null, $parameters = array(), $attributes = array())
    {
        $url = route($route, $parameters);

        $title = (is_null($title)) ? $url : e($title);

        $attributes = HTML::attributes($attributes);

        $iconpart = '<i class="fa fa-' . e($icon) . '"></i>';

        return '<a href="' . $url . '"' . $attributes . '>' . $iconpart . '<span>' . $title . '</span></a>';
    }
}
function getAditionalDataByShortName($short_name, $table)
{
    $aditional_data = ReferencesData::where('short_name', $short_name)
        ->where('table', $table)
        ->first();
        if ($aditional_data) {
            return $aditional_data;
        }
        return $short_name;
  
}

function getReferencesStaticTableBySection($section, $table)
{
    $ref_data = ReferencesData::where([
        'section' => $section,
        'table' => $table,
        'is_visible' => 1
    ])->get();
    if ($ref_data) return $ref_data;

    return false;
}

function getReferencesDataBySection($section, $table)
{
    $rdata_enf_dif = ReferencesData::where([
        'section' => $section,
        'table' => $table,
        'is_visible' => 1
    ])->get();
    if ($rdata_enf_dif) return $rdata_enf_dif;

    return false;
}

function getReferencesTableByCategory($category)
{
    $rollist = DB::table('referencias_tablas')
        ->select('id', 'ref_nombre')
        ->where('categoria', $category)
        //->where('categoria',$category)
        ->get();

    return $rollist;
}

function getDiffDays($fecha_inicio, $fecha_fin)
{
    $inicio = Carbon::parse($fecha_inicio); //moment(vacaciones[0].fecha_inicio, 'YYYY-MM-DD');
    $fin = Carbon::parse($fecha_fin); //moment(vacaciones[0].fecha_fin, 'YYYY-MM-DD');
    return  $inicio->diffInDays($fin, false);
}
function quitarAcentos($cadena)
{
    $acentos = array(
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        'Á' => 'A',
        'É' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ú' => 'U',
        'ü' => 'u',
        'Ü' => 'U',
        'ñ' => 'n',
        'Ñ' => 'N'
        // Puedes agregar más caracteres a esta lista según tus necesidades
    );

    // Reemplazar los caracteres acentuados por sus equivalentes sin acento
    $cadenaSinAcentos = strtr($cadena, $acentos);

    return $cadenaSinAcentos;
}

function ramasDerechoNotificar()
{
    return [
        15,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        31,
        32,
        33,
        35,
        37,
        39,
        40,
        41,
    ];
}

function pdfReportsDataValues()
{
    return [
        'users' => [
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'nombres',
                'name' => 'Nombres',
                'table_name' => 'name',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'apellidos',
                'name' => 'Apellido',
                'table_name' => 'lastname',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'tipo_identificacion',
                'name' => 'Tipo identificación',
                'table_name' => 'tipodoc_id',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'tipo_persona',
                'name' => 'Tipo persona',
                'table_name' => 'tipopers_id',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'numero_identificacion',
                'name' => 'No. identificación',
                'table_name' => 'idnumber',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'telefono',
                'name' => 'Teléfono',
                'table_name' => 'tel1',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'direccion',
                'name' => 'Dirección',
                'table_name' => 'address',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'correo_electronico',
                'name' => 'Correo electrónico',
                'table_name' => 'email',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'estado_civil',
                'name' => 'Estado civil',
                'table_name' => 'estadocivil_id',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'sexo',
                'name' => 'Sexo',
                'table_name' => 'genero_id',
            ],
            [
                'table' => 'users',
                'model' => 'user',
                'short_name' => 'codigo_estudiantil',
                'name' => 'Código estudiantil',
                'table_name' => 'codigo_estudiantil',
            ]
        ],
        'conciliaciones' => [
            [
                'table' => 'conciliaciones',
                'model' => 'conciliacion',
                'short_name' => 'numero_radicado',
                'name' => 'Número de radicado',
                'table_name' => 'num_conciliacion',
            ],
            [
                'table' => 'conciliaciones',
                'model' => 'conciliacion',
                'short_name' => 'fecha_hora_radicado',
                'name' => 'Fecha y hora de radicado',
                'table_name' => 'fecha_radicado',
            ],
            [
                'table' => 'audiencias',
                'model' => 'audiencia',
                'short_name' => 'fecha_hora_audiencia',
                'name' => 'Fecha y hora de audiencia',
                'table_name' => 'fecha_hora',
            ],
            [
                'table' => 'conc_hechos_pretensiones',
                'model' => 'hechos_pretensiones',
                'short_name' => 'pretensiones',
                'name' => 'Pretensiones',
                'table_name' => 'P',
            ],
            [
                'table' => 'conc_hechos_pretensiones',
                'model' => 'hechos_pretensiones',
                'short_name' => 'hechos',
                'name' => 'Hechos',
                'table_name' => 'Hechos',
            ],
            [
                'table' => 'conc_hechos_pretensiones',
                'model' => 'hechos_pretensiones',
                'short_name' => 'anexos',
                'name' => 'Anexos',
                'table_name' => 'A',
            ],

        ]
    ];
}

function obtenerTableName($datos, $shortName)
{
    foreach ($datos as $dato) {
        if ($dato['short_name'] === $shortName) {
            return $dato['table_name'];
        }
    }
    // Devuelve algo predeterminado si no se encuentra el 'short_name'
    return null;
}

function sanear_string($string)
{
    $string = trim($string);
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array(
            "/",
            "¨",
            "º",
            "-",
            "~",
            "#",
            "@",
            "|",
            "!",
            "",
            "·",
            "$",
            "%",
            "&",
            "/",
            "(",
            ")",
            "?",
            "'",
            "¡",
            "¿",
            "[",
            "^",
            "<code>",
            "]",
            "+",
            "}",
            "{",
            "¨",
            "´",
            ">",
            "< ",
            ";",
            ",",
            ":",
            "."
        ),
        '',
        $string
    );
    $string = str_replace(
        array(" "),
        '_',
        $string
    );
    return strtolower($string);
}

?>