<?php

namespace Lia\KernelBundle\Controller;

use Lia\Kernel\CrudBundle\Action\ActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class ControllerBase
    extends Controller
{
    /**
     * Method who can be overridden
     * for doing anything after initialization of container
     */
    protected function __onAfterSetContainer(){

    }

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->__onAfterSetContainer();
    }

    /**
     * Forwards the request to another controller.
     *
     * @param string $controller The controller name (a string like BlogBundle:Post:index)
     * @param array  $path       An array of path parameters
     * @param array  $query      An array of query parameters
     *
     * @return mixed
     */
    public function callAction($controller, array $path = array(), array $query = array())
    {
        $path['_controller'] = $controller;
        return $this->container->get('request_stack')->getCurrentRequest()->duplicate($query, null, $path);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    public function renderView($view, array $parameters = array())
    {
        $parameters['controller'] = $this;
        return parent::renderView($view, $parameters);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['controller'] = $this;
        return parent::render($view, $parameters, $response);
    }

    /**
     * @param $template
     * @param bool $blockOnly
     * @param array $parameters
     * @param Response $response
     * @return mixed
     */
    public function renderByUserGrant($template, $blockOnly=false, array $parameters = array(), Response $response = null)
    {
        $method = 'render'.($blockOnly ? 'View' : '');
        $render = $this->$method($this->buildTemplateFile($template, $blockOnly), $parameters, $response);
        return $render;
    }

    /**
     * Build string
     * Pattern : action.[anonymous|user|admin|...].[block|].html.twig
     *
     * @param string $template
     * @param bool $blockOnly
     * @return string
     *
     */
    protected function buildTemplateFile($template, $blockOnly=false){
        $typesOfAccount = array('admin', 'user');
         return $template
            .'.'.$this->getHighUserGrant($typesOfAccount, $template)
            .($blockOnly ? '.block' : '')
            .'.html.twig'
        ;
    }

    /**
     * @param array $types
     * @return string
     */
    protected function getHighUserGrant(Array $types){
        $type = '';
        foreach($types as $_type){
            if (true === $this->isGranted("ROLE_".strtoupper($_type))) {
                $type = $_type;
                break;
            }
        }
        return ($type ?: 'anonymous');
    }

    /**
     * @param string $action Available : [ index | show | create | edit | delete ]
     * @return ActionInterface
     */
    public function getCrud($action)
    {
        return $this->container->get('lia.factory.crud')->$action($this);
    }
}
