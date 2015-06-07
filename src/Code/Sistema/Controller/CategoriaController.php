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
            $result = $app['categoriaService']->findPagination(0, $this->registros_por_pagina);
            return $app['twig']->render('categoria/categoria.twig', ['categorias' => $result, 'page_atual' => 1, 'numero_paginas' => ceil($app['categoriaService']->getRows() / $this->registros_por_pagina)]);
        })->bind('categorias_listar');

        $controller->get('/page/{page}', function ($page) use ($app) {
            if ($page < 1 or $page > ceil($app['categoriaService']->getRows() / $this->registros_por_pagina)) {
                $page = 1;
            }
            $result = $app['categoriaService']->findPagination((($page - 1) * $this->registros_por_pagina), $this->registros_por_pagina);
            return $app['twig']->render('categoria/categoria.twig', ['categorias' => $result, 'page_atual' => $page, 'numero_paginas' => ceil($app['categoriaService']->getRows() / $this->registros_por_pagina)]);
        })->bind('categorias_listar_pagination');

        $controller->match('/novo', function (Request $request) use ($app) {
            $form = $app['form.factory']->createBuilder(new \Code\Sistema\Form\CategoriaForm())
                    ->getForm();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $serviceManager = $app['categoriaService'];
                $result = $serviceManager->insert($data);
                return $app['twig']->render('categoria/categoria_novo.twig', ["success" => $result, "form" => $form->createView(), "Message" => $serviceManager->getMessage()]);
            }
            return $app['twig']->render('categoria/categoria_novo.twig', ["Message" => array(), "form" => $form->createView()]);
        })->bind('categoria_novo');

        $controller->match('/edit/{id}', function ($id, Request $request) use ($app) {
            $form = $app['form.factory']->createBuilder(new \Code\Sistema\Form\CategoriaForm())
                    ->getForm();
            if ($this->isPost($request)) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $data["id"] = $id;
                    $app['categoriaService']->update($data);
                    return $app->redirect("/categorias");
                }
                return $app['twig']->render('categoria/categoria_edit.twig', ["form" => $form->createView()]);
            }
            $result = $app['categoriaService']->find($id);
            $form->setData($result->toArray());
            return $app['twig']->render('categoria/categoria_edit.twig', ["form" => $form->createView()]);
        })->bind('categoria_edit');

        $controller->get('/delete/{id}', function ($id) use ($app) {
            $app['categoriaService']->delete($id);
            return $app->redirect("/categorias");
        })->bind('categorias_delete');

        return $controller;
    }

    private function isPost($request) {
        if ('POST' == $request->getMethod()) {
            return true;
        }
        return false;
    }

}
