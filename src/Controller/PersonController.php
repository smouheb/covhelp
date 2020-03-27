<?php


namespace App\Controller;


use App\Entity\OptionForHelp;
use App\Entity\Person;
use App\Form\PersonType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    const HELPER = "Helper";

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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

}