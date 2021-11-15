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
            $newActivity = new Activity();
            $newActivity->setName($activity['name']);
            $newActivity->setDuration($activity['duration']);

            $newActivity->setOwner($this->getReference(UserFixtures::USER1));

            $manager->persist($newActivity);
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
