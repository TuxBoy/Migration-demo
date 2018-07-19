<?php
namespace App\Controller;

use App\Entity\Post;
use App\Table\PostTable;
use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use function Sodium\compare;

class PostController
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var PostTable
     */
    private $postTable;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->postTable = new PostTable($this->container->get('db'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function index(Request $request, Response $response): ResponseInterface
    {
        $posts = $this->postTable->getAll();
        return $this->render($response, 'posts.index', compact('posts'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function view(Request $request, Response $response, $args): ResponseInterface
    {
        $post = $this->postTable->findBySlug($args['slug']);
        if (!$post) {
            throw new NotFoundException($request, $response);
        }
        return $this->render($response, 'posts.view', compact('post'));
    }

    /**
     * @param Response $response
     * @param string $viewName
     * @param array $data
     * @return ResponseInterface
     */
    private function render(Response $response, string $viewName, array $data = []): ResponseInterface
    {
        /** @var $view Twig */
        $view     = $this->container->get('view');
        $viewName = str_replace('.', '/', $viewName) . '.twig';
        return $view->render($response, $viewName, $data);
    }

}