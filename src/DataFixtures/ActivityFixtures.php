<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $activities = json_decode(file_get_contents(implode(
            DIRECTORY_SEPARATOR,
            [__DIR__, 
            'data', 
            'activity.json'])), true);

        foreach ($activities as $activity)
        {
            $activity = new Activity();
            $activity->setName($activity['name']);
            $activity->setDuration($activity['duration']);

            $activity->setOwner($this->setReference(UserFixtures::USER));

            $manager->persist($activity);
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
