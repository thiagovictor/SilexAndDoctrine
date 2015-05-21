<?php

namespace Code\Sistema\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoriaAPIController implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        $controller = $app['controllers_factory'];
        
        $controller->get('/', function () use ($app) {
            $result = $app['categoriaService']->findAllToArray();
            return $app->json($result);
        });

        $controller->get('/{id}', function ($id) use ($app) {
            $result = $app['categoriaService']->findToArray($id);
            return $app->json($result);
        });

        $controller->post('/', function (Request $request) use ($app) {
            $serviceManager = $app['categoriaService'];
            $result = $serviceManager->insert($request->request->all());
            return $app->json(["success" => $result, "Message" => $serviceManager->getMessage()]);
        });

        $controller->put('/', function (Request $request) use ($app) {
            $serviceManager = $app['categoriaService'];
            $result = $serviceManager->update($request->request->all());
            return $app->json(["success" => $result, "Message" => $serviceManager->getMessage()]);
        });

        $controller->delete('/{id}', function ($id) use ($app) {
            $serviceManager = $app['categoriaService'];
            $result = $serviceManager->delete($id);
            return $app->json(["success" => $result, "Message" => $serviceManager->getMessage()]);
        });
        
        return $controller;
    }

}
