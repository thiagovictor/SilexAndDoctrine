<?php

namespace Code\Sistema\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ProdutoController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', function () use ($app) {
            $result = $app['produtoService']->findAll();
            return $app['twig']->render('produto/produtos.twig', ['produtos' => $result]);
        })->bind('produtos_listar');

        $controller->get('/novo', function () use ($app) {
            return $app['twig']->render('produto/produto_novo.twig', []);
        })->bind('produto_novo');

        $controller->post('/novo', function (Request $request) use ($app) {
            $app['produtoService']->insert($request->request->all());
            return $app['twig']->render('produto/produto_novo.twig', []);
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
