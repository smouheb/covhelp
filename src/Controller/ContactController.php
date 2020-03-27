<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {

        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            try {
                $data = $form->getData();

                $this->sendMailForContact(
                                            $data->getMessage(),
                                            $data->getSubject(),
                                            $data->getEmail(),
                                            $data->getFirstName(),
                                            $data->getLastName()
                                        );

                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                $this->addFlash('success', 'Thanks for your email, we\'ll be in touch soon');

                return $this->redirectToRoute('home');

            } catch (\Exception $exception){

                $this->logger->error('This is an error while sending the contact message and persisting it '.$exception->getMessage());

                $this->addFlash('error', 'Oups there was an error try again...');

                return $this->redirectToRoute('home');
            }


        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function sendMailForContact( $mes, $subject, $emailFrom, $fristname, $lastname){

        $message = (new Email())
            ->from($emailFrom)
            ->to('smael.mouheb@gmail.com')
            ->subject($subject)
            ->text(
                    'Message from '
                          .$fristname.' '
                          .$lastname.' '
                          .$emailFrom.': '
                          .$mes,
                   'text/html'
                );
        try {
            $this->mailer->send($message);

        } catch (TransportExceptionInterface $e) {

            $this->logger->error('This is the email class '.$e->getMessage());

        }
    }
}
