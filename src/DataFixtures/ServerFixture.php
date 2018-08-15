<?php

namespace App\DataFixtures;

use App\Entity\Server;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ServerFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $server = new Server();
        $server->setId(uniqid());
        $server->setSlideUri('https://aym.arbey.fr/slides/{meeting}/{slide}.jpg');
        $server->setThumbnailUri('https://aym.arbey.fr/slides/{meeting}/{slide}.jpg');
        $manager->persist($server);

        $manager->flush();

        $this->addReference('server', $server);
    }

    public function getOrder() {
        return 10;
    }
}
