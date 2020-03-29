<?php


namespace App\Controller;


use App\Entity\OptionForHelp;
use App\Entity\Person;
use App\Form\PersonRequestType;
use App\Form\PersonType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    const HELPER = "Helper";

    private $logger;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    /**
     * @param Request $request
     * @Route(path="/helpers", name="helpers")
     * @return Response
     */
    public function personHelping(Request $request)
    {
        //dd($request);
        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $data = $form->getData();

            $emailExist = $this->getDoctrine()->getRepository(Person::class)->findBy(['email' => $data->getEmail()]);

            if(empty($emailExist)){

                try {
                    $data->setIsHelping(true);
                    $optObject = new OptionForHelp();
                    foreach ($request->request->get('ListOfOptions') as $options){
                        $objectFromOptions = $this->setObjectForHelpOptions($optObject, $options);
                        $data->addOptionsForHelp($objectFromOptions);
                    }
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($data);
                    $em->flush();

                    $this->addFlash('success', 'Thanks for helping');

                    return $this->redirectToRoute('home');

                } catch (\Exception $exception){

                    $this->addFlash('error', 'Something went wrong... please try again');

                    $this->logger->error($exception->getMessage());

                    return $this->redirectToRoute('home');
                }
            };

            $this->addFlash('error', 'This contact already exists');

            return $this->redirectToRoute('helpers');

        }

        return $this->render("helpers/helpers.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/people-that-help", name="listOfHelpers")
     */
    public function listOfHelpingPeople()
    {

        $lists = $this->getDoctrine()
                     ->getRepository(Person::class)
                     ->peopleThatHelp(self::HELPER);

        return $this->render("listOfPeople/listOfHelpers.html.twig", [
            'lists' => $lists
        ]);
    }

    /**
     * @Route(path="/search-for-help", name="searchForHelp")
     */
    public function searchForhelp(Request $request)
    {
        if(
           $request->request->get('Walking_Dogs') ||
           $request->request->get('Groceries') ||
           $request->request->get('Garbage') ||
           $request->request->get('Dry_Cleaning_pick_up') ||
           $request->request->get('Deliver_Take_away')
        ){

            $query = $this->getDoctrine()->getRepository(Person::class)->personMatchingCriterion($request->request);

            return $this->render('listOfPeople/listOfHelpers.html.twig', [

                'lists' => $query
            ]);
        }

        return $this->render('listOfPeople/searchForHelp.html.twig');
    }

    /**
     * @param Request $request
     * @Route(path="/requestHelp/{id}", name="requestHelp")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function requestHelp(Request $request, $id)
    {
        $form =$this->createForm(PersonRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            try {
                $data = $form->getData();

                $data->setPersonId($id);
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                $this->sendMessageToHelper($data, $id);
                $this->addFlash('success', 'Your message has been sent successfully');

                return $this->redirectToRoute('home');

            } catch (\Exception $exception){

                $this->logger->error('Something went wrong in the requestPerson process: '.$exception->getMessage());

                $this->addFlash('error', 'An error occured please try again or contact us...');

                return $this->redirectToRoute('home');
            }
        }
        return $this->render('listOfPeople/requestForm.html.twig', [
            'form' => $form->createView()
        ]);

        // Send email to the helper
            // Pick his email and send the details of the needy
            // Store message sent to the db

    }

    private function setObjectForHelpOptions(OptionForHelp $forHelp, $request) : OptionForHelp
    {
        switch ($request) {

            case $request === "Groceries":
                $forHelp->setGroceries(true);
                break;

            case $request === "Garbage":
                $forHelp->setGarbage(true);
                break;

            case $request === "WalkingDog":
                $forHelp->setWalkingDog(true);
                break;

            case $request === "DryCleaning":
                $forHelp->setDryCleaning(true);
                break;

            case $request === "DeliverTakeAway":
                $forHelp->setDeliverTakeAway(true);
                break;
        }
        return $forHelp;
    }

    private function sendMessageToHelper(object $data, int $id) : void
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);

        $message = (new Email())
            ->from(new Address('smael.mouheb@gmail.com','Virtual Hand'))
            ->to($person->getEmail())
            ->subject('Somenone needs your help')
            ->html(
                '<h3>Dear '.$person->getFirstname().'</h3>
                         <p>You receive a message from : <strong>'.$data->getFirstname().' '.$data->getLastname().'</strong>
                         <br>
                         <br>
                          This person needs your help and here the contact details as well as the message.
                          <br> 
                          <br> 
                          <strong>
                            <u>Details:</u>
                          </strong>
                          <br>
                          <br>
                          <strong>Email => '.$data->getEmail().'</strong>
                         <br>
                         <strong>Phone => '.$data->getPhone().'</strong> 
                         <br>
                         <br>
                          <strong>This person prefers a contact in : </strong>'.$data->getLanguage().
                          '<br>
                          <br>
                          <strong>Here is the message :</strong>
                          <br>
                          <br>'
                         .$data->getMessage().
                         '<br>
                          <br>
                         Thanks very much for helping out in this time of need
                         <br>
                         <br>
                         Be safe and healthy, with much appreciation
                         <br>
                         <br>
                         The Virtual Hand team
                         </p>'
                        ,
                'text/html'
            );
        try {
            $this->mailer->send($message);

        } catch (TransportExceptionInterface $e) {

            $this->logger->error('This is the email class '.$e->getMessage());

        }
    }

}