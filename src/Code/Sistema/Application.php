<?php

namespace Code\Sistema;

use Silex\Application as ApplicationSilex;
use Code\Sistema\Service\ProdutoService;
use Code\Sistema\Mapper\ProdutoMapper;
use Code\Sistema\Connection\SQLite3Connection;
use Code\Sistema\Entity\Produto;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app['produtoService'] = function () {
            return $produtoService = new ProdutoService(new ProdutoMapper(new SQLite3Connection(new \SQLite3(__DIR__ . "/database/sistema.db"))), new Produto());
        };

        $app->get('/', function () use ($app) {
            return $app['twig']->render('index.twig', []);
        })->bind('inicio');
        
        $app->mount("/produtos", new Controller\ProdutoController());
        $app->mount("/api/produtos", new Controller\ProdutoAPIController);   
    }

}
