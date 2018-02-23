<?php
/**
 * Created by PhpStorm.
 * User: santiagogg
 * Date: 22/02/2018
 * Time: 20:35
 */

namespace Controllers;

use Twig_Environment;

class BaseController
{
    protected $view;
    
    /**
     * BaseController constructor.
     *
     * @param \Twig_Environment $view
     */
    public function __construct(Twig_Environment $view) {
        $this->view = $view;
    }
}