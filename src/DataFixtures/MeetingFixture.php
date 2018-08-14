<?php

namespace App\DataFixtures;

use App\Entity\Meeting;
use App\Entity\Server;
use App\Entity\Slide;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MeetingFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Server $server */
        $server = $this->getReference('server');

        $meeting = new Meeting();
        $meeting->setId(uniqid());
        $meeting->setTitle('Best presentation ever !');

        $meeting->setServer($server);

        $slides = [];
        for ($i = 1 ; $i <= 12 ; $i++) {
            $slide = new Slide(uniqid(), 'Page #' . $i);
            $manager->persist($slide);
            $slides[] = $slide;
        }
        $meeting->setSlides($slides);
        $manager->persist($meeting);

        $manager->flush();

        $this->addReference('meeting', $meeting);
    }

    public function getOrder() {
        return 20;
    }
}
