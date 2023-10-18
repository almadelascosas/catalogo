<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['js_version'] = rand(0,999999); //4.2;
/*
DEFINIMOS EL MENU
*/
$config['menu_app']['dashboard']['content'] = "Inicio";
$config['menu_app']['dashboard']['icon'] = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';
$config['menu_app']['dashboard']['permiso'] = "1,2,3,4,5";

$config['menu_app']['federaciones']['content'] = "Federaciones";
$config['menu_app']['federaciones']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>';
$config['menu_app']['federaciones']['submenu']['listado']['content'] = "Lista";
$config['menu_app']['federaciones']['submenu']['listado']['permiso'] = "1";
$config['menu_app']['federaciones']['submenu']['agregar']['content'] = "Crear";
$config['menu_app']['federaciones']['submenu']['agregar']['permiso'] = "1";
$config['menu_app']['federaciones']['permiso'] = "1";

//$config['menu_app']['ligas']['content'] = "Ligas";
//$config['menu_app']['ligas']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="18" cy="18" r="3"></circle><circle cx="6" cy="6" r="3"></circle><path d="M13 6h3a2 2 0 0 1 2 2v7"></path><line x1="6" y1="9" x2="6" y2="21"></line></svg>';
//$config['menu_app']['ligas']['submenu']['listado'] = "Lista";
//$config['menu_app']['ligas']['submenu']['agregar'] = "Crear";
//$config['menu_app']['ligas']['permiso'] = "1";

$config['menu_app']['club']['content'] = "Clubes";
$config['menu_app']['club']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>';
$config['menu_app']['club']['submenu']['listado']['content'] = "Lista";
$config['menu_app']['club']['submenu']['listado']['permiso'] = "1,2";
$config['menu_app']['club']['submenu']['agregar']['content'] = "Agregar";
$config['menu_app']['club']['submenu']['agregar']['permiso'] = "1,2";
$config['menu_app']['club']['permiso'] = "1,2";

$config['menu_app']['atletas']['content'] = "Registros";
$config['menu_app']['atletas']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
$config['menu_app']['atletas']['submenu']['listado']['content'] = "Lista";
$config['menu_app']['atletas']['submenu']['listado']['permiso'] = "1,2,3";
$config['menu_app']['atletas']['submenu']['agregar']['content'] = "Crear";
$config['menu_app']['atletas']['submenu']['agregar']['permiso'] = "1,2,3";
$config['menu_app']['atletas']['permiso'] = "1,2,3";

$config['menu_app']['tickets']['content'] = "Tickets";
$config['menu_app']['tickets']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon></svg>';
$config['menu_app']['tickets']['submenu']['listado']['content'] = "Listado";
$config['menu_app']['tickets']['submenu']['listado']['permiso'] = "1,2,3";
$config['menu_app']['tickets']['submenu']['add']['content'] = "Abrir Ticket";
$config['menu_app']['tickets']['submenu']['add']['permiso'] = "1,2,3";
$config['menu_app']['tickets']['permiso'] = "1,2,3";

$config['menu_app']['comunicado']['content'] = "Comunicado";
$config['menu_app']['comunicado']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon></svg>';
$config['menu_app']['comunicado']['submenu']['listado']['content'] = "Lista";
$config['menu_app']['comunicado']['submenu']['listado']['permiso'] = "1,2,3";
$config['menu_app']['comunicado']['submenu']['agregar']['content'] = "Agregar";
$config['menu_app']['comunicado']['submenu']['agregar']['permiso'] = "1,2";
$config['menu_app']['comunicado']['permiso'] = "1,2,3";

$config['menu_app']['usuarios']['content'] = "Usuarios";
$config['menu_app']['usuarios']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
$config['menu_app']['usuarios']['submenu']['listado']['content'] = "Lista";
$config['menu_app']['usuarios']['submenu']['listado']['permiso'] = "1";
$config['menu_app']['usuarios']['submenu']['agregar']['content'] = "Crear";
$config['menu_app']['usuarios']['submenu']['agregar']['permiso'] = "1";
$config['menu_app']['usuarios']['permiso'] = "1";

$config['menu_app']['eventos']['content'] = "Eventos";
$config['menu_app']['eventos']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
$config['menu_app']['eventos']['submenu']['listado']['content'] = "Lista";
$config['menu_app']['eventos']['submenu']['listado']['permiso'] = "1,2,3";
$config['menu_app']['eventos']['submenu']['agregar']['content'] = "Crear";
$config['menu_app']['eventos']['submenu']['agregar']['permiso'] = "1,2";
$config['menu_app']['eventos']['permiso'] = "1,2,3";

$config['menu_app']['exportacion']['content'] = "Exportación";
$config['menu_app']['exportacion']['icon'] = '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';

$config['menu_app']['exportacion']['permiso'] = "1,2";

$config['mostrar_paginacion'] = 20;

// Bootstrap
$config['bootstrap']['col-12'] = 'col-lg-12 col-md-12 col-sm-12 col-12';
$config['bootstrap']['col-11'] = 'col-lg-11 col-md-11 col-sm-11 col-12';
$config['bootstrap']['col-11_total'] = 'col-lg-11 col-md-11 col-sm-11 col-11';
$config['bootstrap']['col-10'] = 'col-lg-10 col-md-10 col-sm-10 col-12';
$config['bootstrap']['col-10_total'] = 'col-lg-10 col-md-10 col-sm-10 col-10';
$config['bootstrap']['col-9'] = 'col-lg-9 col-md-9 col-sm-9 col-12';
$config['bootstrap']['col-9_total'] = 'col-lg-9 col-md-9 col-sm-9 col-9';
$config['bootstrap']['col-8'] = 'col-lg-8 col-md-8 col-sm-8 col-12';
$config['bootstrap']['col-8_total'] = 'col-lg-8 col-md-8 col-sm-8 col-8';
$config['bootstrap']['col-7'] = 'col-lg-7 col-md-7 col-sm-7 col-12';
$config['bootstrap']['col-7_total'] = 'col-lg-7 col-md-7 col-sm-7 col-7';
$config['bootstrap']['col-6'] = 'col-lg-6 col-md-6 col-sm-6 col-12';
$config['bootstrap']['col-6_total'] = 'col-lg-6 col-md-6 col-sm-6 col-6';
$config['bootstrap']['col-5'] = 'col-lg-5 col-md-5 col-sm-5 col-12';
$config['bootstrap']['col-5_total'] = 'col-lg-5 col-md-5 col-sm-5 col-5';
$config['bootstrap']['col-4'] = 'col-lg-4 col-md-4 col-sm-4 col-12';
$config['bootstrap']['col-4_tablet'] = 'col-lg-4 col-md-4 col-sm-7 col-12';
$config['bootstrap']['col-4_total'] = 'col-lg-4 col-md-4 col-sm-4 col-4';
$config['bootstrap']['col-3'] = 'col-lg-3 col-md-3 col-sm-3 col-12';
$config['bootstrap']['col-3_tablet'] = 'col-lg-3 col-md-3 col-sm-6 col-12';
$config['bootstrap']['col-3_total'] = 'col-lg-3 col-md-3 col-sm-3 col-3';
$config['bootstrap']['col-2'] = 'col-lg-2 col-md-2 col-sm-2 col-12';
$config['bootstrap']['col-2_tablet'] = 'col-lg-2 col-md-2 col-sm-5 col-12';
$config['bootstrap']['col-2_total'] = 'col-lg-2 col-md-2 col-sm-2 col-2';
$config['bootstrap']['col-1'] = 'col-lg-1 col-md-1 col-sm-1 col-12';
$config['bootstrap']['col-1_total'] = 'col-lg-1 col-md-1 col-sm-1 col-1';

$config['bootstrap']['offset-6'] = 'offset-lg-6 offset-md-6 offset-sm-6';
$config['bootstrap']['offset-5'] = 'offset-lg-5 offset-md-5 offset-sm-5';
$config['bootstrap']['offset-4'] = 'offset-lg-4 offset-md-4 offset-sm-4';
$config['bootstrap']['offset-3'] = 'offset-lg-3 offset-md-3 offset-sm-3';
$config['bootstrap']['offset-2'] = 'offset-lg-2 offset-md-2 offset-sm-2';
$config['bootstrap']['offset-1'] = 'offset-lg-1 offset-md-1 offset-sm-1';

$config['tipos_pagos'][0] = "Recibo";
$config['tipos_pagos'][1] = "Transferencia";
$config['tipos_pagos'][2] = "Contado";

$config['error_prefix_message'] = '<div class="alert alert-danger alert-sm"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
$config['error_suffix_message'] = '</div>';

$config['success_prefix_message'] = '<div class="alert alert-success alert-sm"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
$config['success_suffix_message'] = '</div>';

$config['warning_prefix_message'] = '<div class="alert alert-warning alert-sm"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
$config['warning_suffix_message'] = '</div>';
