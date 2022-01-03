<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Form\MessagesAdminType;
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
     * @Route("/send_admin", name="send_admin")
     */
    public function send_admin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Messages;
        $form = $this->createform(MessagesAdminType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSenderadmin($this->getUser());

            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($message);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash("message", "Message envoyé avec succés.");
            return $this->redirectToRoute("admin_home");
        }

        return $this->render("messages/send_admin.html.twig", [
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
     * @Route("/received_admin", name="received_admin")
     */
    public function received_admin(): Response
    {
        return $this->render('messages/received_admin.html.twig');
    }

    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }
    /**
     * @Route("/sent_admin", name="sent_admin")
     */
    public function sent_admin(): Response
    {
        return $this->render('messages/sent_admin.html.twig');
    }

    /**
     * @Route("/read_received/{id}", name="read_received")
     */
    public function read_received(Messages $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsread(true);
        $entityManager->persist($message);

        // cette classe permet de génèrer et éxecuter la requête SQL
        $entityManager->flush();
        return $this->render('messages/read.received.html.twig', compact("message"));
    }

    /**
     * @Route("/read_received_admin/{id}", name="read_received_admin")
     */
    public function read_received_admin(Messages $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsread(true);
        $entityManager->persist($message);

        // cette classe permet de génèrer et éxecuter la requête SQL
        $entityManager->flush();
        return $this->render('messages/read.received_admin.html.twig', compact("message"));
    }


    /**
     * @Route("/read_sent/{id}", name="read_sent")
     */
    public function read_sent(Messages $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsread(true);
        $entityManager->persist($message);

        // cette classe permet de génèrer et éxecuter la requête SQL
        $entityManager->flush();
        return $this->render('messages/read.sent.html.twig', compact("message"));
    }


    /**
     * @Route("/read_sent_admin/{id}", name="read_sent_admin")
     */
    public function read_sent_admin(Messages $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsread(true);
        $entityManager->persist($message);

        // cette classe permet de génèrer et éxecuter la requête SQL
        $entityManager->flush();
        return $this->render('messages/read.sent_admin.html.twig', compact("message"));
    }

}
