<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $orders = $this->openOrdersFile();

        $user = new User();
        $user->setUsername('admin')->setPassword($this->encoder->hashPassword($user, 'S3cr3T+'));

        $manager->persist($user);

        $manager->flush();
    }

    public function openOrdersFile()
    {
        if (file_exists('data/orders.xml')) {
            return simplexml_load_file('data/orders.xml');
        }

        exit('Echec lors de l\'ouverture du fichier' . PHP_EOL);
    }
}
