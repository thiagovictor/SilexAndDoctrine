<?php

namespace Code\Sistema\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ProdutoController implements ControllerProviderInterface {
    private $registros_por_pagina = 5;
    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', function () use ($app) {
            $result = $app['produtoService']->findPagination(0,  $this->registros_por_pagina);
            return $app['twig']->render('produto/produtos.twig', ['produtos' => $result, 'page_atual'=>1,'numero_paginas'=>ceil($app['produtoService']->getRows()/$this->registros_por_pagina)]);
        })->bind('produtos_listar');
        
         $controller->get('/{page}', function ($page) use ($app) {
            if($page < 1 or $page > ceil($app['produtoService']->getRows()/$this->registros_por_pagina) ){
                $page = 1;
            }
            $result = $app['produtoService']->findPagination((($page-1)*$this->registros_por_pagina),  $this->registros_por_pagina);
            return $app['twig']->render('produto/produtos.twig', ['produtos' => $result, 'page_atual'=>$page, 'numero_paginas'=>ceil($app['produtoService']->getRows()/$this->registros_por_pagina)]);
        })->bind('produtos_listar_pagination');

        $controller->get('/novo', function () use ($app) {
            return $app['twig']->render('produto/produto_novo.twig', []);
        })->bind('produto_novo');

        $controller->post('/novo', function (Request $request) use ($app) {
            $serviceManager = $app['produtoService'];
            $result = $serviceManager->insert($request->request->all());
            return $app['twig']->render('produto/produto_novo.twig', ["success" => $result, "Message" => $serviceManager->getMessage()]);
        })->bind('produto_novo_post');

        $controller->get('/edit/{id}', function ($id) use ($app) {
            $result = $app['produtoService']->find($id);
            return $app['twig']->render('produto/produto_edit.twig', ["produto" => $result]);
        })->bind('produto_edit');

        $controller->post('/edit', function (Request $request) use ($app) {
            $app['produtoService']->update($request->request->all());
            return $app->redirect("/produtos");
        })->bind('produto_edit_post');

        $controller->get('/delete/{id}', function ($id) use ($app) {
            $app['produtoService']->delete($id);
            return $app->redirect("/produtos");
        })->bind('produtos_delete');

        return $controller;
    }

}
