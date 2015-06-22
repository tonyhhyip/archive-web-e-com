<?hh //strict

use \Composer\Autoload\ClassLoader;

$loader = new ClassLoader();
$loader->setPsr4('App\\', __DIR__);
$loader->register(true);