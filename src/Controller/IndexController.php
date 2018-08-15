<?php

namespace App\Controller;

use App\Repository\MeetingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index(MeetingRepository $meeting_repository)
    {
        $meetings = $meeting_repository->findBy([], array('id' => 'DESC'), 20);

        return $this->render('index/index.html.twig', [
            'ws_url' => 'aym.arbey.fr/ws',
            'meetings' => $meetings
        ]);
    }
}
