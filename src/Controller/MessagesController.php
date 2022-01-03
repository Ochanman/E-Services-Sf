<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
   
    /**
     * @Route("/send", name="send")
     */
    public function send(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Messages;
        $form = $this->createform(MessagesType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $message->setSender($this->getUser());

            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($message);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash("message", "Message envoyé avec succés.");
            return $this->redirectToRoute("user_info_track", ['id' => $message->getId()]);
        }

        return $this->render("messages/send.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }

    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(Messages $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsread(true);
        $entityManager->persist($message);

        // cette classe permet de génèrer et éxecuter la requête SQL
        $entityManager->flush();
        return $this->render('messages/read.html.twig', compact("message"));
    }

}
