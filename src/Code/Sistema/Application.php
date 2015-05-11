<?php

namespace Code\Sistema;

use Silex\Application as ApplicationSilex;
use Code\Sistema\Service\ProdutoService;
use Code\Sistema\Entity\Produto;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app['produtoService'] = function () use($app){
            return $produtoService = new ProdutoService($app['EntityManager'], new Produto());
        };

        $app->get('/', function () use ($app) {
            return $app['twig']->render('index.twig', []);
        })->bind('inicio');
        
        $app->mount("/produtos", new Controller\ProdutoController());
        $app->mount("/api/produtos", new Controller\ProdutoAPIController);   
    }

}
