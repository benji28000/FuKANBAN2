<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Form\TasksTypeStatut;
use App\Repository\StatutRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Statut;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

#[Route('/tasks')]
class TasksController extends AbstractController
{
    #[Route('/', name: 'app_tasks_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository): Response
    {
        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , TokenInterface $token): Response
    {
        $user = $token->getUser();
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->addUser($user);
            $date_now = new \DateTime();
            $task->setDateCreation($date_now);
            $task->setDateModification($date_now);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/new/{statut}', name: 'app_tasks_new_statut', methods: ['GET', 'POST'])]
    public function newStatut(Request $request, EntityManagerInterface $entityManager, $statut , TokenInterface $token): Response
    {
        $user = $token->getUser();
        $task = new Tasks();
        $form = $this->createForm(TasksTypeStatut::class, $task);
        $form->handleRequest($request);

        // Récupérer le statut à partir de l'ID fourni
        $statutEntity = $entityManager->getRepository(Statut::class)->find($statut);



        $task->setStatut($statutEntity);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->addUser($user);
            $date_now = new \DateTime();
            $task->setDateCreation($date_now);
            $task->setDateModification($date_now);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('fukanban', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now_date = new \DateTime();
            $task->setDateModification($now_date);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('fukanban', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_tasks_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {

            $entityManager->remove($task);
            $entityManager->flush();


        return $this->redirectToRoute('fukanban', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/update-task-status/{id}/{newstatut}", name: "update_task_status" , methods: ['GET', 'POST'])]
    public function updateTaskStatus(Request $request, EntityManagerInterface $entityManager, ?String $newstatut, ?int $id, StatutRepository $statutRepository)
    {
        $statut = $statutRepository->findBy(["libelle" => $newstatut]);

        $task = $entityManager->getRepository(Tasks::class)->find($id);

        $task->setStatut($statut[0]);
        $now_date = new \DateTime();
        $task->setDateModification($now_date);
        $entityManager->flush();
        $entityManager->persist($task);


        return new JsonResponse(['message' => 'Task status updated successfully']);
    }


}
