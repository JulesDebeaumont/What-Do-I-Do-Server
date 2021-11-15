<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER1 = 'user1';
    public const USER2 = 'user2';
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('random@yahoo.fr');
        $password = $this->encoder->encodePassword($user, 'changeMe');
        $user->setPassword($password);
        $manager->persist($user);
        $this->addReference(self::USER1, $user);

        $user2 = new User();
        $user2->setEmail('random2@yahoo.fr');
        $password = $this->encoder->encodePassword($user2, 'changeMe');
        $user2->setPassword($password);
        $manager->persist($user2);
        $this->setReference(self::USER2, $user2);

        $manager->flush();
    }
}
