<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $contact = new Contact();

        if ($this->getUser()){
            $contact->setLastname($this->getUser()->getLastname())
                    ->setFirstname($this->getUser()->getFirstname())
                    ->setEmail($this->getUser()->getEmail());
        }

        $form    = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
           $contact = $form->getData();
           

            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre formulaire a été envoyé avec succès !'
            );


            return $this->redirectToRoute('contact_app');

        }

        

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
