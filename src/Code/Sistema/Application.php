<?php

namespace Code\Sistema;

use Silex\Application as ApplicationSilex;
use Code\Sistema\Service\ProdutoService;
use Code\Sistema\Entity\Produto;
use Code\Sistema\Validator\NumericValidador;
use Code\Sistema\Validator\IsBlankValidador;

class Application extends ApplicationSilex {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $app = $this;

        $app['produtoService'] = function () use($app) {
            $produtoService = new ProdutoService($app['EntityManager'], new Produto());
            $produtoService->setArrayValidators([
                'valor' => new NumericValidador(),
                //'valor' => new IsBlankValidador(),
                'nome' => new IsBlankValidador(),
                'descricao' => new IsBlankValidador()
                    ]
            );
            return $produtoService;
        };

        $app->get('/', function () use ($app) {
            return $app['twig']->render('index.twig', []);
        })->bind('inicio');

        $app->mount("/produtos", new Controller\ProdutoController());
        $app->mount("/api/produtos", new Controller\ProdutoAPIController);
    }

}
