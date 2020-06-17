<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index()
    {
        return $this->render('Index/index.html.twig');
    }
}