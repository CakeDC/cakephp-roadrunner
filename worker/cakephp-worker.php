<?php
require __DIR__ . "/vendor/autoload.php";
$bridge = new \CakeDC\Roadrunner\Bridge(__DIR__);
$bridge->bootstrap(null, null, null);
$relay = new \Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new \Spiral\RoadRunner\PSR7Client(new \Spiral\RoadRunner\Worker($relay));

while ($req = $psr7->acceptRequest()) {
//     \CakeDC\Api\Service\ServiceRegistry::getServiceLocator()->clear(); // reset API cache if you're using CakeDC/Api plugin
    $psr7response = $bridge->handle($req);
    $psr7->respond($psr7response);
}
