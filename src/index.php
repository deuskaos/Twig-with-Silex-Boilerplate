<?php
require_once __DIR__.'/vendor/silex.phar';
$app = new Silex\Application();

use Symfony\Component\HttpFoundation\Response;

// DEBUG
if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	$app['debug'] = true;
	
// ERROR 404
} else {
	$app['debug'] = false;
	$app->error(function (\Exception $e, $code) {
		switch ($code) {
			case 404:
				$message = 'Lo sentimos, la página a la que intenta acceder no existe.';
			default:
				$message = 'Lo sentimos, la página a la que intenta acceder no existe.';
		}
		return new Response($message, $code);
	});	
}

$data = array();
$data['siteName'] = 'Nombre de la Página';
$data['defaultDescription'] = 'Descripción por defecto de la página';

// SWIFTMAILER
$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.class_path'  => __DIR__.'/vendor/Swift-4.1.3/lib/classes',
));

// TWIG
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
	// 'twig.options' => array('cache' => __DIR__.'/../cache'),
));

$app->match('/{pageName}', function ($pageName) use($app, $data) {
	$data ['seccion'] = ucwords($pageName);
    return $app['twig'] -> render(strtolower($pageName).'.html', $data);
}) 
-> value('pageName', 'inicio');

$app->run();
?>