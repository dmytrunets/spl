<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\RequestGame;
use AppBundle\Entity\Team;
use AppBundle\Entity\Tender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/", name="list")
     */
    public function searchAction(Request $request)
    {
        $filters = array();

        if ($request->getMethod() == 'POST') {
            $filters['city'] = $request->get('city');
            $filters['district'] = $request->get('district');
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle\Entity\Tender')->getTenderList($filters);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            7/*limit per page*/
        );

        return $this->render("Default/index.html.twig", ['pagination' => $pagination]);
    }

    /**
     * @Route("/myaccount/tenders", name="_my_account")
     */
    public function bidTenderAction(Request $request)
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle\Entity\Tender')->getMyTenderList($userId);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

//        $confirmedTenders = $em->getRepository('AppBundle\Entity\Tender')->getConfirmedRequests($userId)
//            ->getResult();
//
//        $formatted = array();
//        foreach ($confirmedTenders as $tender)
//        {
//            $formatted[$tender->getId()] = $tender->getRequests();
//        }

        $data = ['pagination' => $pagination, 'formatted' => $formatted ];
        return $this->render("Account/bid_tender.html.twig", $data);
    }

    /**
     * @Route("/myaccount/requests", name="_ask_tender")
     */
    public function askTenderAction(Request $request)
    {
        $userId = $this->getUser()->getId();


        $em = $this->getDoctrine()->getManager();

        $team = $em->getRepository('AppBundle\Entity\Team')->findOneBy(array('user' => $userId));
        $query = $em->getRepository('AppBundle\Entity\RequestGame')
            ->getMyRequestedTenderList($team->getId());

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render("Account/ask_tender.html.twig", ['pagination' => $pagination]);
    }

    /**
     * @Route("/post_new_tender", name="_post_new_tender")
     */
    public function newTenderAction(Request $request)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        $user = $this->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('_new_user');
        }

        if ($request->getMethod() == 'POST') {
            try {
                $city = $request->get('city');
                $districtId = $request->get('district');
                $geoPoint = $request->get('geo_point');
                $date = $request->get('date');
                $time = $request->get('time');
                $maxTeam = $request->get('max_team');
                $maxPlayer = $request->get('max_player');
                $dateObj = new \DateTime("$date $time");

                $em = $this->getDoctrine()->getManager();

                $tender = new Tender();
                $tender->setCityId($city);
                $tender->setDistrictId($districtId);
                $tender->setGeoPoint($geoPoint);
                $tender->setDateTime($dateObj);
                $tender->setOrganizer($user);

                $em->persist($tender);
                $em->flush();

                return $this->redirectToRoute('_my_account');
            } catch (\Exception $e) {

            }
        }

        return $this->render("Account/new_tender.html.twig", []);
    }

    /**
     * @Route("/settings", name="_edit_profile")
     */
    public function editProfileAction(Request $request)
    {
        $user = $this->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('_new_user');
        }

        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle\Entity\Team')->findOneBy(array('user' => $user));

        if (is_null($team)) {
            $team = new Team();
        }

        if ($request->getMethod() == 'POST') {
            $manager = $request->get('manager');
            $phone = $request->get('phone');
            $city = $request->get('city');
            $teamName = $request->get('team_name');
            $description = $request->get('description');

            $team->setUser($this->getUser());
            $team->setManager($manager);
            $team->setPhone($phone);
            $team->setCity($city);
            $team->setTitle($teamName);
            $team->setDescription($description);

            $em->persist($team);
            $em->flush();
        }

        return $this->render("Account/settings.html.twig", ['team' => $team]);
    }

    /**
     * @Route("/tender/{tender_id}", name="_view_tender")
     */
    public function viewTenderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tender = $em->getRepository('AppBundle\Entity\Tender')->find($request->get('tender_id'));

        return $this->render("Default/tender_page.html.twig", ['tender'=>$tender]);
    }

    /**
     * @Route("/myaccount/messages", name="_list_messages")
     */
    public function listMessagesAction(Request $request)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        $teamId = $this->getUser()->getTeam()->getId();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle\Entity\Message')->getUserMessages($teamId);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render("Account/messages.html.twig", ['messages' => $pagination]);
    }

    /**
     * @Route("/myaccount/messages/{tread_id}", name="_view_tread")
     */
    public function viewTreadAction(Request $request)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        $treadId = $request->get('tread_id');

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle\Entity\Message')->getTreadMessages($treadId);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render("Account/view_tread.html.twig", ['messages' => $pagination, 'currentUser' => $this->getUser()]);
    }

    /**
     * @Route("/myaccount/tender/{tender_id}/send_message", name="_send_message")
     */
    public function sendMessageAction(Request $request)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        $em = $this->getDoctrine()->getManager();

        $tenderId = $request->get('tender_id');
        $tender = $em->getRepository('AppBundle\Entity\Tender')->find($tenderId);

        $messageInTreadExists = $em->getRepository('AppBundle\Entity\Message')
            ->findOneBy(array('tender'=>$tender, 'sender'=>$this->getUser()->getTeam()));

        if ($request->getMethod() == 'POST') {
            $content = $request->get('content');

            $message = new Message();

            if ($messageInTreadExists){
                $message->setTreadId($messageInTreadExists->getTreadId());
            } else {
                $message->setTreadId(uniqid());
            }

            $message->setContent($content);
            $message->setTender($tender);
            $message->setReceiver($tender->getOrganizer()->getTeam());
            $message->setSender($this->getUser()->getTeam());
            $message->setCreatedAt(new \DateTime());

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('_list_messages');
        }

        return $this->render("Account/send_message.html.twig", ['tender'=>$tender]);
    }

    /**
     * @Route("/ask_query/{tender_id}", name="_ask_query")
     */
    public function askQueryAction(Request $req)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        if (is_null($this->getUser())) {
            $this->redirectToRoute('login');
        }

        $team = $this->getUser()->getTeam();
        if (is_null($team)) {
            $this->redirectToRoute('_edit_profile');
        }

        $em = $this->getDoctrine()->getManager();

        $tenderId = $req->get('tender_id');
        $tender = $em->getRepository('AppBundle\Entity\Tender')->find($tenderId);

        if (is_null($tender)) {
            throw new \InvalidArgumentException("Tender with id $tenderId not exists");
        }

        $request = new RequestGame();
        $request->setTeam($team);
        $request->setTender($tender);

        $em->persist($request);
        $em->flush();

        return $this->redirectToRoute('_ask_tender');
//        return $this->render("Account/ask_tender.html.twig", []);
    }

    /**
     * @Route("/tender/{tender_id}/requests", name="_request_list")
     */
    public function requestListAction(Request $request)
    {
        $tenderId = $request->get('tender_id');

        $em = $this->getDoctrine()->getManager();
        $tender = $em->getRepository('AppBundle\Entity\Tender')->find($tenderId);

        $qb = $em->getRepository('AppBundle\Entity\RequestGame')->getRequestsByTender($tenderId);
        $requests = $qb->getResult();

        return $this->render("Account/confirm_request.html.twig", ['tender'=>$tender, 'requests'=>$requests]);
    }

    /**
     * @Route("/confirm_request/{tender_id}/{request_id}", name="_confirm_request")
     */
    public function confirmRequestAction(Request $req)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        $tenderId = $req->get('tender_id');
        $requestId = $req->get('request_id');

        $em = $this->getDoctrine()->getManager();
        $request = $em->getRepository('AppBundle\Entity\RequestGame')->find($requestId);
        $request->setStatus(RequestGame::STATUS_APPROVED);
        $em->persist($request);

        $tender = $em->getRepository('AppBundle\Entity\Tender')->find($tenderId);
        $tender->setStatus(Tender::STATUS_CLOSED);
        $em->persist($tender);

        $em->flush();

        return $this->redirectToRoute('_my_account');
    }

    /**
     * @Route("/reject_request/{tender_id}/{request_id}", name="_reject_request")
     */
    public function rejectRequestAction(Request $req)
    {
        $check = $this->checkSettings();
        if ($check instanceof Response){
            return $check;
        }

        $requestId = $req->get('request_id');

        $em = $this->getDoctrine()->getManager();
        $request = $em->getRepository('AppBundle\Entity\RequestGame')->find($requestId);
        $request->setStatus(RequestGame::STATUS_REJECTED);
        $em->persist($request);

        $em->flush();

        return $this->redirectToRoute('_my_account');
    }

    /**
     * @Route("/team/{team_id}", name="_team_page")
     */
    public function viewTeamAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle\Entity\Team')->find($req->get('team_id'));

        return $this->render("Default/team.html.twig", ['team' => $team]);
    }

    private function checkSettings()
    {
        $user = $this->getUser();

        if (!$user){
            return $this->redirectToRoute('fos_user_security_login');
        }

        $team = $user->getTeam();

        if (!$team) {
            return $this->redirectToRoute('_edit_profile');
        }
    }

    /**
     * @Route("/list_closed_games", name="_list_closed_games")
     * @return Response
     */
    public function listClosedGames(Request $request)
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle\Entity\Tender')->getMyTenderClosedList($userId);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render("Default/list_closed_games.html.twig", ['pagination' => $pagination]);

    }
}
