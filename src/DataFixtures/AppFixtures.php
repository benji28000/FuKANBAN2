<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateurs;
use App\Entity\Projets;
use App\Entity\Statut;
use App\Entity\Tasks;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $statuts = [];
        $utilisateurs = [];
        $projets = [];
        $tasks = [];

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
        $statutinprogress->setLibelle('in-progress');
        $statuts[] = $statutinprogress;
        $manager->persist($statutinprogress);

        $statutlive = new Statut();
        $statutlive->setLibelle('live');
        $statuts[] = $statutlive;
        $manager->persist($statutlive);

        $manager->flush();

        for ($i = 0; $i < 8; $i++) {
            $user = new Utilisateurs();
            $user->setName('user' . $i);
            $user->setMail('user' . $i . '@gmail.com');
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $utilisateurs[] = $user;
            $manager->persist($user);
        }
        for ($j = 0; $j < 4; $j++) {
            $project = new Projets();
            $project->setName('project' . $j);
            $project->setDescription('description' . $j);
            $project->setDateCreation(new \DateTime());
            $project->setDateModification(new \DateTime());
            $randomuserprojet = $utilisateurs[array_rand($utilisateurs)];
            $project->setUtilisateurs($randomuserprojet);
            $projets[] = $project;
            $manager->persist($project);
        }


        for ($k = 0; $k < 100; $k++) {
            $task = new Tasks();
            $task->setTitle('task' . $k);
            $task->setDescription('description' . $k);
            $task->setDateCreation(new \DateTime());
            $task->setDateModification(new \DateTime());

                    // Set a random Statut for each Task
            $randomStatut = $statuts[array_rand($statuts)];
            $task->setStatut($randomStatut);

                        // Set a random Project for each Task
            $randomProject = $projets[array_rand($projets)];
            $task->setProject($randomProject);

                        // Set a random User for each Task
            $randomUser = $utilisateurs[array_rand($utilisateurs)];
            $task->addUser($randomUser);
            $tasks[] = $task;
            $manager->persist($task);
        }



        $manager->flush();




    }
}
