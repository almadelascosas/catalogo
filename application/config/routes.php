<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'home';

$route['dashboard'] = 'dashboard';

$route['mi-cuenta'] = 'cuenta';
$route['mi-cuenta/orders'] = 'cuenta/orders';
$route['mi-cuenta/ordersview'] = 'cuenta/ordersview';
$route['mi-cuenta/ordersview/(:any)'] = 'cuenta/ordersview/$1';
$route['mi-cuenta/address'] = 'cuenta/address';
$route['mi-cuenta/edit-address'] = 'cuenta/editAddress';
$route['mi-cuenta/edit-address/(:any)'] = 'cuenta/editAddress/$1';
$route['mi-cuenta/direccion-predeterminada/(:any)/(:any)'] = 'cuenta/dirpredeterminada/$1/$2';
$route['mi-cuenta/delete-address/(:any)'] = 'cuenta/deleteAddress/$1';
$route['mi-cuenta/edit-account'] = 'cuenta/editAccount';

$route['rastrea-tu-pedido'] = 'rastreo/rastreaTuPedido';
$route['rastreo/(:any)'] = 'rastreo/$1';

$route['regalos-corporativos'] = 'corporativos';

$route['checkout/finalizar-compra'] = 'checkout/finalizarCompra';
$route['checkout/finalizar-compra/(:any)'] = 'checkout/finalizarCompra/$1';
$route['checkout/promo'] = 'checkout/noLoginProm';

$route['politica-de-privacidad'] = 'tienda/politicas';
$route['condiciones'] = 'tienda/condiciones';
$route['eliminacion-datos'] = 'tienda/eliminardatos';

//$route['club/editar/(:any)/(:any)'] = 'club/editar/$1/$2';

/***********************************
    TIENDA ONLINE
***********************************/

$route['404_override'] = 'home/pagina_no_encontrada';
$route['translate_uri_dashes'] = FALSE;

$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8

$route['checkout/thanks/(:any)/pedido-(:any)/(:any)'] = 'checkout/thanks/$1/$2/$3';