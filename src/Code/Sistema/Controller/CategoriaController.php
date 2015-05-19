<?php

namespace Code\Sistema\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CategoriaController implements ControllerProviderInterface {
    private $registros_por_pagina = 5;
    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', function () use ($app) {
            $result = $app['categoriaService']->findPagination(0,  $this->registros_por_pagina);
            return $app['twig']->render('categoria/categoria.twig', ['categorias' => $result, 'page_atual'=>1,'numero_paginas'=>ceil($app['categoriaService']->getRows()/$this->registros_por_pagina)]);
        })->bind('categorias_listar');
        
        $controller->get('/page/{page}', function ($page) use ($app) {
            if($page < 1 or $page > ceil($app['categoriaService']->getRows()/$this->registros_por_pagina) ){
                $page = 1;
            }
            $result = $app['categoriaService']->findPagination((($page-1)*$this->registros_por_pagina),  $this->registros_por_pagina);
            return $app['twig']->render('categoria/categoria.twig', ['categorias' => $result, 'page_atual'=>$page, 'numero_paginas'=>ceil($app['categoriaService']->getRows()/$this->registros_por_pagina)]);
        })->bind('categorias_listar_pagination');

        $controller->get('/novo', function () use ($app) {
            return $app['twig']->render('categoria/categoria_novo.twig', ["Message"=>array()]);
        })->bind('categoria_novo');

        $controller->post('/novo', function (Request $request) use ($app) {
            $serviceManager = $app['categoriaService'];
            $result = $serviceManager->insert($request->request->all());
            return $app['twig']->render('categoria/categoria_novo.twig', ["success" => $result, "Message" => $serviceManager->getMessage()]);
        })->bind('categoria_novo_post');

        $controller->get('/edit/{id}', function ($id) use ($app) {
            $result = $app['categoriaService']->find($id);
            return $app['twig']->render('categoria/categoria_edit.twig', ["categoria" => $result]);
        })->bind('categoria_edit');

        $controller->post('/edit', function (Request $request) use ($app) {
            $app['categoriaService']->update($request->request->all());
            return $app->redirect("/categorias");
        })->bind('categoria_edit_post');

        $controller->get('/delete/{id}', function ($id) use ($app) {
            $app['categoriaService']->delete($id);
            return $app->redirect("/categorias");
        })->bind('categorias_delete');

        return $controller;
    }

}
