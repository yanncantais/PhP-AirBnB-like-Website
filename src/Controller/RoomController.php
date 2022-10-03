<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/list", name="room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }
    

    /**
     * @Route("/new", name="room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Change content-type according to image's
            $imagefile = $room->getImageFile();
            if($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $room->setContentType($mimetype);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/room/{id}", name="room_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Room $room): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * Mark a task as current priority in the user's session
     *
     * @Route("/mark/{id}", name="room_mark", requirements={ "id": "\d+"}, methods="GET")
     */
    public function markAction(Room $room): Response
    {
        $urgents = $this->get('session')->get('urgents');
        if( ! is_array($urgents) ) {
            $urgents = array();
        }
        
        // si l'identifiant n'est pas présent dans le tableau des urgents, l'ajouter
        if (! in_array($room->getId(), $urgents) )
        {
            $urgents[] = $room->getId();
        }
        else
        // sinon, le retirer du tableau
        {
            $urgents = array_diff($urgents, array($room->getId()));
        }
        $this->get('session')->set('urgents', $urgents);
        dump($urgents);
        return $this->redirectToRoute('room_show',
            ['id' => $room->getId()]);
        
    }
    /**
     * @Route("/mark/list", name="room_mark_index", methods={"GET"})
     */
    public function markIndex()
    {
        $em=$this->getDoctrine()->getManager();
        $urgents = $this->get('session')->get('urgents');
        dump($urgents);
        if(empty($urgents)){
            $this->get('session')->getFlashBag()->add('message', 'no room marked');
            return $this->redirectToRoute('room_index',
                [], Response::HTTP_SEE_OTHER);
            
        }
        foreach($urgents as $urgents){
            $room=$em->getRepository(Room::class)->find($urgents);
            $rooms[]=$room;
        }
        return $this->render('room/mark.html.twig',array('rooms'=> $rooms,
            
        ));
    }
}
