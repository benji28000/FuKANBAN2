<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProjetsRepository;
use App\Repository\TasksRepository;
use App\Repository\StatutRepository;
use App\Repository\UtilisateursRepository;

class FukanbanController extends AbstractController
{


    #[Route('/' , name: 'fukanban' , methods: ['GET'])]
    public function mainpage(  TasksRepository $tasksRepository, StatutRepository $statutRepository, ProjetsRepository $projetsRepository, UtilisateursRepository $utilisateursRepository )
    {

            return $this->render('Fukanban/Fukanban.html.twig', [
                "tasks" => $tasksRepository->findAll(),
                "statuts" => $statutRepository->findAll(),
                "projets" => $projetsRepository->findAll(),
                "utilisateurs" => $utilisateursRepository->findAll(),
                "idprojet" => null,

            ]);

    }



    #[Route('/home/{idprojet}', name: 'fukanban_projet' , methods: ['GET'])]
    public function projet(?int $idprojet,   TasksRepository $tasksRepository, StatutRepository $statutRepository, ProjetsRepository $projetsRepository, UtilisateursRepository $utilisateursRepository )
    {

        return $this->render('Fukanban/Fukanban.html.twig', [
            "tasks" => $tasksRepository->findBy(["project" => $idprojet]),
            "statuts" => $statutRepository->findAll(),
            "projets" => $projetsRepository->findAll(),
            "utilisateurs" => $utilisateursRepository->findAll(),
            "idprojet" => $idprojet,

        ]);

    }

}

