<?php

namespace Code\Sistema;

use Silex\Application as ApplicationSilex;
use Code\Sistema\Service\ProdutoService;
use Code\Sistema\Service\CategoriaSevice;
use Code\Sistema\Service\TagSevice;
use Code\Sistema\Entity\Produto;
use Code\Sistema\Entity\Categoria;
use Code\Sistema\Entity\Tag;
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
                'nome' => new IsBlankValidador(),
                'tags' => new IsBlankValidador(),
                'descricao' => new IsBlankValidador()
                    ]
            );
            return $produtoService;
        };

        $app['categoriaService'] = function () use($app) {
            $categoriaService = new CategoriaSevice($app['EntityManager'], new Categoria());
            $categoriaService->setValidators('nome', new IsBlankValidador());
            return $categoriaService;
        };

        $app['tagService'] = function () use($app) {
            $tagService = new TagSevice($app['EntityManager'], new Tag());
            $tagService->setValidators('nome', new IsBlankValidador());
            return $tagService;
        };

        $app->get('/', function () use ($app) {
            return $app['twig']->render('index.twig', []);
        })->bind('inicio');

        $app->mount("/produtos", new Controller\ProdutoController());
        $app->mount("/categorias", new Controller\CategoriaController());
        $app->mount("/tags", new Controller\TagController());
        $app->mount("/api/produtos", new Controller\ProdutoAPIController());
        $app->mount("/api/tags", new Controller\TagAPIController());
        $app->mount("/api/categorias", new Controller\CategoriaAPIController());
    }

}
