<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER = 'user';
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

        $this->addReference(self::USER, $user);

        $manager->persist($user);

        $manager->flush();
    }
}
