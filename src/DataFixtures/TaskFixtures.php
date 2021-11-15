<?php

namespace App\DataFixtures;

use App\Entity\Task;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tasks = json_decode(file_get_contents(implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'data',
                'task.json'
            ]
        )), true);

        foreach ($tasks as $task) {
            $newTask = new Task();
            $newTask->setName($task['name']);
            $newTask->setIsActivated($task['isActivated']);
            $newTask->setRepeatInterval($task['repeatInterval']);
            $newTask->setStart(DateTime::createFromFormat(DateTimeInterface::ATOM, $task['start']));
            $newTask->setMessage($task['message']);
            $newTask->setOwner($this->getReference(UserFixtures::USER1));

            $manager->persist($newTask);
        }

        // Setting a specific 
        // TODO This should go into a test..
        $anotherTask = new Task();
        $anotherTask->setName('Buy milk');
        $anotherTask->setIsActivated(false);
        $anotherTask->setRepeatInterval(0);
        $anotherTask->setStart(DateTime::createFromFormat(DateTimeInterface::ATOM, "2022-05-11T11:00:01+141"));
        $anotherTask->setMessage('Buy mjölk go go!');
        $anotherTask->setOwner($this->getReference(UserFixtures::USER2));
        $manager->persist($anotherTask);


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
