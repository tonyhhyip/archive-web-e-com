<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Twig_Loader_Chain;
use Twig_Loader_Filesystem;
use Twig_Environment;
use \Exception;


class AppKernel
{

    /**
     * @var \Symfony\Component\Routing\Matcher\UrlMatcher
     */
	private $matcher;

    /**
     * @var \Twig_Environment
     */
    private $environment;

	public function __construct()
	{
        $collection = $this->loadViews();
        $this->createMatcher($collection);
        $this->createEnvironment();
	}

    private function loadViews()
    {
        if (filemtime(__DIR__ . '/../storage/views') < filemtime(__DIR__ . '/../config/views.json')) {
            $collection = new RouteCollection();
            $views = (array) json_decode(file_get_contents(__DIR__ . '/../config/views.json', true));
            foreach ($views as $viewContent) {
                $view = (array)$viewContent;
                $route = new Route($view['path'], $view);
                $collection->add($view['name'], $route);
            }
            file_put_contents(__DIR__ . '/../storage/views', serialize($collection));
        } else {
            $collection = file_get_contents(__DIR__ . '/../storage/views');
            $collection = unserialize($collection);
        }

        return $collection;
    }

    private function createMatcher(RouteCollection $collection)
    {
        $this->matcher = new UrlMatcher($collection, new RequestContext());
    }


    private function createEnvironment()
    {
        $loader = new Twig_Loader_Chain();
        $fileLoader = new Twig_Loader_Filesystem();
        $fileLoader->addPath(dirname(__DIR__) . '/resources');
        $loader->addLoader($fileLoader);
        $this->environment = new Twig_Environment($loader, [dirname(__DIR__) . '/storage']);
    }

    private static function notFoundAction()
    {
        $response = new Response();
        $response->setStatusCode(404);
        return $response;
    }

    public function handle(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->matcher->setContext($context);
        try {
            $route = $this->matcher->match($request->getRequestUri());
        } catch (Exception $e) {
            try {
                $route = $this->matcher->match($request->getRequestUri() . '.html');
            } catch (Exception $e) {
                return self::notFoundAction();
            }
        }

        $content = $this->environment->render($route['view'] . '.twig');

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setCharset('UTF-8');
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        $response->setContent($content);

        return $response;
    }
}
