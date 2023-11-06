<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FukanbanController extends AbstractController
{
    #[Route('/' , name: 'fukanban')]
    public function mainpage()
    {
        return $this->render('Fukanban/Fukanban.html.twig');
    }
}

