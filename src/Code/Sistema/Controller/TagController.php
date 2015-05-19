<?php

namespace Code\Sistema\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class TagController implements ControllerProviderInterface {
    private $registros_por_pagina = 5;
    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', function () use ($app) {
            $result = $app['tagService']->findPagination(0,  $this->registros_por_pagina);
            return $app['twig']->render('tag/tag.twig', ['tags' => $result, 'page_atual'=>1,'numero_paginas'=>ceil($app['tagService']->getRows()/$this->registros_por_pagina)]);
        })->bind('tags_listar');
        
        $controller->get('/page/{page}', function ($page) use ($app) {
            if($page < 1 or $page > ceil($app['tagService']->getRows()/$this->registros_por_pagina) ){
                $page = 1;
            }
            $result = $app['tagService']->findPagination((($page-1)*$this->registros_por_pagina),  $this->registros_por_pagina);
            return $app['twig']->render('tag/tag.twig', ['tags' => $result, 'page_atual'=>$page, 'numero_paginas'=>ceil($app['tagService']->getRows()/$this->registros_por_pagina)]);
        })->bind('tags_listar_pagination');

        $controller->get('/novo', function () use ($app) {
            return $app['twig']->render('tag/tag_novo.twig', ["Message"=>array()]);
        })->bind('tag_novo');

        $controller->post('/novo', function (Request $request) use ($app) {
            $serviceManager = $app['tagService'];
            $result = $serviceManager->insert($request->request->all());
            return $app['twig']->render('tag/tag_novo.twig', ["success" => $result, "Message" => $serviceManager->getMessage()]);
        })->bind('tag_novo_post');

        $controller->get('/edit/{id}', function ($id) use ($app) {
            $result = $app['tagService']->find($id);
            return $app['twig']->render('tag/tag_edit.twig', ["tag" => $result]);
        })->bind('tag_edit');

        $controller->post('/edit', function (Request $request) use ($app) {
            $app['tagService']->update($request->request->all());
            return $app->redirect("/tags");
        })->bind('tag_edit_post');

        $controller->get('/delete/{id}', function ($id) use ($app) {
            $app['tagService']->delete($id);
            return $app->redirect("/tags");
        })->bind('tags_delete');

        return $controller;
    }

}
