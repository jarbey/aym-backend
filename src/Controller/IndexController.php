<?php

namespace App\Controller;

use App\Form\PPTConvertType;
use App\Model\PPTConvertModel;
use App\Repository\MeetingRepository;
use App\Service\MeetingManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, MeetingManager $meeting_manager, MeetingRepository $meeting_repository) {
        $ppt_convert = new PPTConvertModel();
        $form = $this->createForm(PPTConvertType::class, $ppt_convert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meeting_manager->createMeetingFromPPT($ppt_convert->getPpt());
        }

        $meetings = $meeting_repository->findBy([], array('id' => 'DESC'), 20);

		$file = file(__DIR__ . '/../../var/log/dev.websocketserver.log');
		$logs = [];
		for ($i = max(0, count($file) - 100); $i < count($file); $i++) {
			$logs[] = $file[$i];
		}

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
            'ws_url' => 'aym.arbey.fr/ws',
            'meetings' => $meetings,
			'log' => join("\n", $logs)
        ]);
    }
	/**
	 * @Route("/meeting", name="meeting")
	 */
	public function meeting() {
		return $this->render('index/meeting.html.twig', [
			'css' => scandir('/public/static/css/')[2],
			'js' => scandir('/public/static/js/')[2]
		]);
	}
}
