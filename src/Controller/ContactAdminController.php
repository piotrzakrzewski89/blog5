<?php

namespace App\Controller;

use App\Form\ContactAdminType;
use App\Entity\ContactAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

class ContactAdminController extends AbstractController
{
    /**
     * @Route("/contact_admin", name="contact_admin")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAdmin(Request $request, MailerInterface $mailer)
    {
        $contact = new ContactAdmin();
        $form = $this->createForm(ContactAdminType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formName = $form->get('name')->getData();
            $formEmail = $form->get('email')->getData();
            $formSubject = $form->get('subject')->getData();
            $formBody = $form->get('body')->getData();
            $currentDate = new \DateTime();

            $message = (new Email())
                ->from('piotrzakrzewski@piotrzakrzewski89.pl')
                ->to('piotrzakrzewski@piotrzakrzewski89.pl')
                ->subject($formSubject)
                ->html($currentDate->format('Y-m-d H:i:s') . '<br><br>Wiadomosc od: ' . $formName . ' : ' . $formEmail . '<br><br>' . $formBody);

            try {
                $mailer->send($message);
                $this->addFlash('success', 'Wysłano wiadomość !');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
            }
            return $this->redirectToRoute('main');
        }

        return $this->render('contact_admin/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
