<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tasks = json_decode(file_get_contents(implode(
            DIRECTORY_SEPARATOR,
            [__DIR__, 
            'data', 
            'task.json'])), true);

        foreach ($tasks as $task)
        {
            $newTask = new Task();
            $newTask->setName($task['name']);
            $newTask->setIsActivated($task['isActivated']);
            $newTask->setStart(new Date($task['start']));
            $newTask->setMessage($task['message']);

            $newTask->setOwner($this->setReference(UserFixtures::USER));

            $manager->persist($newTask);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return[
            UserFixtures::class,
        ];
    }
}
