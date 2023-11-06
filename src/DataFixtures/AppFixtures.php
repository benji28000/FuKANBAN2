<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateurs;
use App\Entity\Projets;
use App\Entity\Statut;
use App\Entity\Tasks;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statuts = [];

        $statutbacklog = new Statut();
        $statutbacklog->setLibelle('backlog');
        $statuts[] = $statutbacklog;
        $manager->persist($statutbacklog);

        $statutrunning = new Statut();
        $statutrunning->setLibelle('running');
        $statuts[] = $statutrunning;
        $manager->persist($statutrunning);

        $statutEvalutating = new Statut();
        $statutEvalutating->setLibelle('evaluating');
        $statuts[] = $statutEvalutating;
        $manager->persist($statutEvalutating);

        $statutinprogress = new Statut();
        $statutinprogress->setLibelle('in progress');
        $statuts[] = $statutinprogress;
        $manager->persist($statutinprogress);

        $statutlive = new Statut();
        $statutlive->setLibelle('live');
        $statuts[] = $statutlive;
        $manager->persist($statutlive);

        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
            $user = new Utilisateurs();
            $user->setName('user' . $i);
            $user->setMail('user' . $i . '@gmail.com');
            $user->setPassword('password' . $i);
            $user->setRole('user');
            $manager->persist($user);

            for ($j = 0; $j < 2; $j++) {
                $project = new Projets();
                $project->setName('project' . $j);
                $project->setDescription('description' . $j);
                $project->setDateCreation(new \DateTime());
                $project->setDateModification(new \DateTime());
                $project->setUtilisateurs($user);
                $manager->persist($project);

                for ($k = 0; $k < 5; $k++) {
                    $task = new Tasks();
                    $task->setTitle('task' . $k);
                    $task->setDescription('description' . $k);
                    $task->setDateCreation(new \DateTime());
                    $task->setDateModification(new \DateTime());

                    // Set a random Statut for each Task
                    $randomStatut = $statuts[array_rand($statuts)];
                    $task->setStatut($randomStatut);

                    $task->setProject($project);
                    $task->addUser($user);
                    $manager->persist($task);
                }
            }
        }

        $manager->flush();




    }
}
