<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Basis');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function () {
    return view("hal404");
});
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Basis::index');
$routes->get('/bookdata', 'Basis::data');
$routes->get('/bookraw', 'Basis::getData');
$routes->get('/about', 'Basis::tentang');
$routes->post('/addbook', 'Basis::TambahData');
$routes->post('/getbook', 'Basis::AmbilData');
$routes->post('/updatebook', 'Basis::UpdateData');
$routes->post('/delbook', 'Basis::HapusData');
$routes->get('/rekapdashboard/(:any)', 'Basis::RekapDashboard');
$routes->get('/customer', 'Basis::Pelanggan');
$routes->post('/cari', 'Basis::caribykeyword');
$routes->get('/pdfstatis', 'Basis::cetakpdf1');
$routes->get('/pdfdinamis', 'Basis::cetakpdf2');
$routes->get('/excelstatis', 'Basis::cetakexcel1');
$routes->get('/exceldinamis', 'Basis::cetakexcel2');
$routes->get('/grafik/(:any)', 'Basis::getGrafik');
$routes->get('/backupfile', 'Basis::backup');
$routes->post('/restorefile', 'Basis::restore');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
