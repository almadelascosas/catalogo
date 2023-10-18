<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH.'third_party/google-api-php-client/vendor/autoload.php');
function obtener_cliente()
{
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig(CLIENT_SECRET_PATH);
    $client->setDeveloperKey(DEVELOPER_KEY_GCAL);
    $client->setRedirectUri(REDIRECT_GCAL);
    $client->setAccessType("offline");
    $client->setApprovalPrompt('force');
    return $client;
}

function autenticar($codigo)
{
    $client = obtener_cliente();
    $client->authenticate($codigo);
    $mi_access_token = $client->getAccessToken();


    $respuesta = array();
    $respuesta['mensaje'] = "Cuenta Google Sincronizada";
    $respuesta['mensaje_tipo'] = "success";
    try {
        $CI =& get_instance();
        $CI->load->model('usuarios_model');
        $_SESSION['token_google'] = json_encode($mi_access_token);
        $CI->usuarios_model->actualizar_token($_SESSION['idusuario'], $_SESSION['token_google']);
        unset($_SESSION['url_google']);
    } catch (Exception $e) {
        $respuesta['mensaje'] = "Error en la Sincronización";
        $respuesta['mensaje_tipo'] = "danger";
    }

    return $respuesta;
}

function generar_enlace_sincronizar()
{
    $client = obtener_cliente();
    return $client->createAuthUrl();
}

function sincronizar_tarea_usuarios($datos)
{
    $datos_tarea = $datos['data'];
    $hora_fin_tarea = sprintf("%02d", intval($datos_tarea['la_hora_tarea'])+1).":".$datos_tarea['el_minuto_tarea'];
    $mi_evento = array(
    'summary' => $datos_tarea['nombreTarea'],
    'location' => 'SISFARMA',
    'description' => $datos_tarea['descripcion'],
    'start' => array(
      'dateTime' => date("c", strtotime($datos_tarea['f_inicio']." ".$datos_tarea['la_hora_tarea'].":".$datos_tarea['el_minuto_tarea'].":00")),
      'timeZone' => "Europe/Madrid"
    ),
    'end' => array(
      'dateTime' => date("c", strtotime($datos_tarea['f_inicio']." ".$hora_fin_tarea.":00")),
      'timeZone' => "Europe/Madrid"
    )
  );

    $CI =& get_instance();
    $mis_recordatorios = array();
    //Insertamos por defecto 3 días antes
    $f_recordatorio = restar_dias_laborables($datos_tarea['f_inicio'], 3);

    $dias_restar = calcular_dias($f_recordatorio, $datos_tarea['f_inicio']);

    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => ((24 * 60)*$dias_restar)));

    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => 10)); //Recordatorio por defecto

    if (count($datos_tarea['recordatorios'])>0) {
        $CI->load->helper('commun');
        foreach ($datos_tarea['recordatorios'] as $recordatorio) {
            if ($recordatorio['tiempo']==0) {
                $dias = calcular_dias_laborables($recordatorio['fechaRecordatorio'], $datos_tarea['f_inicio']);
                $tiempo = (24 * 60)*$dias;
            } else {
                $tiempo = $recordatorio['tiempo'];
            }
            array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
        }
    }
    if ($datos_tarea['estado']==2) {
        $mi_evento['colorId'] = 10;
    }
    $mi_evento['reminders'] =  array(
    'useDefault' => false,
    'overrides' => $mis_recordatorios
    );
    $CI->load->model('usuarios_model');
    $CI->load->model('tareas_model');
    foreach ($datos_tarea['usuarios_tarea'] as $usu) {
        if (isset($usu['token_google']) && $usu['token_google']) {
            $client = obtener_cliente();
            $mi_access_token = json_decode($usu['token_google'], true);
            $client->setAccessToken($mi_access_token);
            if ($client->isAccessTokenExpired()) {
                try {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    $nuevo_access_token = json_encode($client->getAccessToken());
                    $CI->usuarios_model->actualizar_token($usu['idusuario'], $nuevo_access_token);
                } catch (Exception $e) {
                }
            }

            $service = new Google_Service_Calendar($client);
            $calendarId = 'primary';

            $event = new Google_Service_Calendar_Event($mi_evento);
            try {
                $event = $service->events->insert($calendarId, $event);
                $CI->tareas_model->actualizar_tarea_usuario($datos_tarea['idtarea'], $usu['idusuario'], $event['id']);
            } catch (Exception $e) {
            }
        } //Existe el acceso_token
    }
}

function desincronizar_tarea_usuarios($datos)
{
    $datos_tarea = $datos['data'];
    foreach ($datos_tarea['usuarios_tarea'] as $usu) {
        if (isset($usu['token_google']) && $usu['token_google'] && $usu['evento_google']!="") {
            $client = obtener_cliente();
            $mi_access_token = json_decode($usu['token_google'], true);
            $client->setAccessToken($mi_access_token);
            if ($client->isAccessTokenExpired()) {
                try {
                    $CI =& get_instance();
                    $CI->load->model('usuarios_model');
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    $nuevo_access_token = json_encode($client->getAccessToken());
                    $CI->usuarios_model->actualizar_token($usu['idusuario'], $nuevo_access_token);
                } catch (Exception $e) {
                }
            }
            $service = new Google_Service_Calendar($client);
            $calendarId = 'primary';
            try {
                $service->events->delete($calendarId, $usu['evento_google']);
            } catch (Exception $e) {
            }
        }
    }
}

function sincronizar_recordatorio_usuario($datos)
{
    $datos_tarea = $datos['data'];
    $hora_fin_tarea = sprintf("%02d", intval($datos_tarea['demo_la_hora'])+1).":".$datos_tarea['demo_el_minuto'];
    $mi_evento = array(
    'summary' => "Demostración Farmacia: ".$datos_tarea['nombreRecordatorio'],
    'location' => 'SISFARMA',
    'description' => "Farmacia: ".$datos_tarea['farmacia']." Telefono: ".$datos_tarea['telefono'],
    'start' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaDemo']." ".$datos_tarea['demo_la_hora'].":".$datos_tarea['demo_el_minuto'].":00")),
      'timeZone' => "Europe/Madrid"
    ),
    'end' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaDemo']." ".$hora_fin_tarea.":00")),
      'timeZone' => "Europe/Madrid"
    )
  );

    $mis_recordatorios = array();
    $tiempo = (24 * 60); //un dia antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $tiempo = 30; //30 min antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $mi_evento['reminders'] =  array(
    'useDefault' => false,
    'overrides' => $mis_recordatorios
    );
    $mi_evento['colorId'] = 11; //Amarillo
    $CI =& get_instance();
    $CI->load->model('usuarios_model');
    $CI->load->model('recordatorios_model');

    if (isset($datos_tarea['token_google_demo']) && $datos_tarea['token_google_demo']) {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_demo'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioDemo'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }

        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';

        $event = new Google_Service_Calendar_Event($mi_evento);
        try {
            $event = $service->events->insert($calendarId, $event);
            $CI->recordatorios_model->actualizar_recordatorio_usuario($datos_tarea['idrecordatorio'], $datos_tarea['idusuarioDemo'], $event['id']);
        } catch (Exception $e) {
            echo "Aqui Recordatorio ";
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
    } //Existe el acceso_token
}

function desincronizar_recordatorio_usuario($datos)
{
    $datos_tarea = $datos['data'];

    if (isset($datos_tarea['token_google_demo']) && $datos_tarea['token_google_demo'] && $datos_tarea['evento_googleDemo']!="") {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_demo'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $CI =& get_instance();
                $CI->load->model('usuarios_model');
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioDemo'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }
        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';
        try {
            $service->events->delete($calendarId, $datos_tarea['evento_googleDemo']);
        } catch (Exception $e) {
        }
    }
}

function sincronizar_segdemo_usuario($datos)
{
    $datos_tarea = $datos['data'];
    $mi_evento = array(
    'summary' => "Seguimiento Demostración Farmacia: ".$datos_tarea['nombreRecordatorio'],
    'location' => 'SISFARMA',
    'description' => "Farmacia: ".$datos_tarea['farmacia']." Telefono: ".$datos_tarea['telefono'],
    'start' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaSegDemo']." ".$datos_tarea['horaDemo'].":00")),
      'timeZone' => "Europe/Madrid"
    ),
    'end' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaSegDemo']." ".$datos_tarea['horaDemo'].":00") + 300), //Add 5min
      'timeZone' => "Europe/Madrid"
    )
  );

    $mis_recordatorios = array();
    $tiempo = 30; //30 min antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $mi_evento['reminders'] =  array(
    'useDefault' => false,
    'overrides' => $mis_recordatorios
    );
    $mi_evento['colorId'] = 11; //Amarillo
    $CI =& get_instance();
    $CI->load->model('usuarios_model');
    $CI->load->model('recordatorios_model');

    if (isset($datos_tarea['token_google_demo']) && $datos_tarea['token_google_demo']) {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_demo'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioDemo'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }

        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';

        $event = new Google_Service_Calendar_Event($mi_evento);
        try {
            $event = $service->events->insert($calendarId, $event);
            $CI->recordatorios_model->actualizar_segdemo_usuario($datos_tarea['idrecordatorio'], $datos_tarea['idusuarioDemo'], $event['id']);
        } catch (Exception $e) {
            echo "Aqui Seg DemoRecordatorio ";
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
    } //Existe el acceso_token
}

function desincronizar_segdemo_usuario($datos)
{
    $datos_tarea = $datos['data'];

    if (isset($datos_tarea['token_google_demo']) && $datos_tarea['token_google_demo'] && $datos_tarea['evento_googleSegDemo']!="") {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_demo'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $CI =& get_instance();
                $CI->load->model('usuarios_model');
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioDemo'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }
        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';
        try {
            $service->events->delete($calendarId, $datos_tarea['evento_googleSegDemo']);
        } catch (Exception $e) {
        }
    }
} //desincronizar SEG DEMO




function sincronizar_recordatorio_usuarioProxVisita($datos)
{
    $datos_tarea = $datos['data'];
    $hora_fin_tarea = sprintf("%02d", intval($datos_tarea['proxvisita_la_hora'])+1).":".$datos_tarea['proxvisita_el_minuto'];
    $mi_evento = array(
    'summary' => "Prox. Llamada: ".$datos_tarea['nombreRecordatorio'],
    'location' => 'SISFARMA',
    'description' => "Farmacia: ".$datos_tarea['farmacia']." Telefono: ".$datos_tarea['telefono'],
    'start' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaProxVisita']." ".$datos_tarea['proxvisita_la_hora'].":".$datos_tarea['proxvisita_el_minuto'].":00")),
      'timeZone' => "Europe/Madrid"
    ),
    'end' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaProxVisita']." ".$datos_tarea['horaProxVisita'].":00") + 300), //Add 5min
      'timeZone' => "Europe/Madrid"
    )
  );

    $mis_recordatorios = array();
    $tiempo = (24 * 60); //un dia antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $tiempo = 30; //30 min antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $mi_evento['reminders'] =  array(
    'useDefault' => false,
    'overrides' => $mis_recordatorios
    );
    $mi_evento['colorId'] = 11; //Azul
    $CI =& get_instance();
    $CI->load->model('usuarios_model');
    $CI->load->model('recordatorios_model');

    if (isset($datos_tarea['token_google_demo']) && $datos_tarea['token_google_demo']) {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_demo'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioDemo'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }

        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';

        $event = new Google_Service_Calendar_Event($mi_evento);
        try {
            $event = $service->events->insert($calendarId, $event);
            $CI->recordatorios_model->actualizar_recordatorio_usuarioProxVisita($datos_tarea['idrecordatorio'], $datos_tarea['idusuarioDemo'], $event['id']);
        } catch (Exception $e) {
            echo "Aqui Prox Visita ";
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
    } //Existe el acceso_token
}

function desincronizar_recordatorio_usuarioProxVisita($datos)
{
    $datos_tarea = $datos['data'];
    if (isset($datos_tarea['token_google_demo']) && $datos_tarea['token_google_demo'] && $datos_tarea['evento_googleProxVisita']!="") {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_demo'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $CI =& get_instance();
                $CI->load->model('usuarios_model');
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioDemo'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }
        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';
        try {
            $service->events->delete($calendarId, $datos_tarea['evento_googleProxVisita']);
        } catch (Exception $e) {
        }
    }
}



function sincronizar_recordatorio_usuarioComercial($datos)
{
    $datos_tarea = $datos['data'];
    $hora_fin_tarea = sprintf("%02d", intval($datos_tarea['visita_la_hora'])+1).":".$datos_tarea['visita_el_minuto'];
    $mi_evento = array(
    'summary' => "Visita a Farmacia: ".$datos_tarea['nombreRecordatorio'],
    'location' => 'SISFARMA',
    'description' => "Farmacia: ".$datos_tarea['farmacia']." Telefono: ".$datos_tarea['telefono'],
    'start' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaVisita']." ".$datos_tarea['visita_la_hora'].":".$datos_tarea['visita_el_minuto'].":00")),
      'timeZone' => "Europe/Madrid"
    ),
    'end' => array(
      'dateTime' => date("c", strtotime($datos_tarea['fechaVisita']." ".$hora_fin_tarea.":00")),
      'timeZone' => "Europe/Madrid"
    )
  );

    $mis_recordatorios = array();
    $tiempo = (24 * 60); //un dia antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $tiempo = 30; //30 min antes
    array_push($mis_recordatorios, array('method' => 'popup', 'minutes' => $tiempo));
    $mi_evento['reminders'] =  array(
    'useDefault' => false,
    'overrides' => $mis_recordatorios
    );
    $mi_evento['colorId'] = 11; //Azul
    $CI =& get_instance();
    $CI->load->model('usuarios_model');
    $CI->load->model('recordatorios_model');

    if (isset($datos_tarea['token_google_comercial']) && $datos_tarea['token_google_comercial']) {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_comercial'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioComercial'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }

        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';

        $event = new Google_Service_Calendar_Event($mi_evento);
        try {
            $event = $service->events->insert($calendarId, $event);
            $CI->recordatorios_model->actualizar_recordatorio_usuarioComercial($datos_tarea['idrecordatorio'], $datos_tarea['idusuarioComercial'], $event['id']);
        } catch (Exception $e) {
            echo "Aqui Comercial ";
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
    } //Existe el acceso_token
}



function desincronizar_recordatorio_usuarioComercial($datos)
{
    $datos_tarea = $datos['data'];

    if (isset($datos_tarea['token_google_comercial']) && $datos_tarea['token_google_comercial'] && $datos_tarea['evento_googleVisita']!="") {
        $client = obtener_cliente();
        $mi_access_token = json_decode($datos_tarea['token_google_comercial'], true);
        $client->setAccessToken($mi_access_token);
        if ($client->isAccessTokenExpired()) {
            try {
                $CI =& get_instance();
                $CI->load->model('usuarios_model');
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $nuevo_access_token = json_encode($client->getAccessToken());
                $CI->usuarios_model->actualizar_token($datos_tarea['idusuarioComercial'], $nuevo_access_token);
            } catch (Exception $e) {
            }
        }
        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';
        try {
            $service->events->delete($calendarId, $datos_tarea['evento_googleVisita']);
        } catch (Exception $e) {
        }
    }
}
