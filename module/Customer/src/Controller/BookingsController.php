<?php
namespace Customer\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use General\Service\GeneralService;
use Doctrine\ORM\EntityManager;
use Customer\Service\BookingService;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use General\Entity\NumberOfSeat;
use Doctrine\ORM\Query;
use Laminas\Mvc\MvcEvent;
use General\Service\FlutterwaveService;
use WasabiLib\Ajax\Response;
use WasabiLib\Ajax\GritterMessage;
use WasabiLib\Ajax\Redirect;

/**
 *
 * @author otaba
 *        
 */
class BookingsController extends AbstractActionController
{

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var BookingService
     */
    private $bookingService;

    /**
     *
     * @var FlutterwaveService
     */
    private $flutterwaveService;

    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        $this->redirectPlugin()->redirectToLogout();
        
        return $response;
    }

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function numberofseatsAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $em = $this->entityManager;
        $repo = $em->getRepository(NumberOfSeat::class);
        $data = $repo->createQueryBuilder("n")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    /**
     * Use this to initiate the booking
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function initiateBookingAction()
    {
        $bookingService = $this->bookingService;
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        
        $em = $this->entityManager;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            
            $bookingSession = $this->bookingService->getBookingSession();
            if ($post["pickUpAddress"] != "") {
                // getDistance from distance matrix
                // Set Value
                $bookingSession->pickUpPlaceId = $post["pickUpPlaceId"];
                $bookingSession->destinationPlaceId = $post["destinationPlaceId"];
                $dm = $bookingService->distanceMatrix();
                
                // Set Submitted Data in session
                $bookingService->setRequestSession($post);
                $bookingSession->selectedBookingClass = $post["bookingClass"];
                $bookingSession->selectedNumberOfSeat = $post["numberOfSeats"];
//                 var_dump($post['pickUpTime']);
                $bookingSession->pickupDate = $post["pickUpDate"];
                $bookingSession->pickupTime = $post["pickUpTime"];
                $bookingSession->isReturnTrip = $post['returnTrip'];
                // Calculate Price
                $distanceValue = $dm->rows[0]->elements[0]->distance->value;
                $distanceText = $dm->rows[0]->elements[0]->distance->text;
                if($bookingSession->isReturnTrip == 'true'){
//                     $bookingSession->returnDate = $post["returndate"]; 
                }
                $price = $bookingService->setDmDistance($distanceValue)->priceCalculator();
                $timeText = $dm->rows[0]->elements[0]->duration->text;
                $timeValue = $dm->rows[0]->elements[0]->duration->value;
                
                $bookingSession->distanceValue = $distanceValue;
                $bookingSession->distanceText = $distanceText;
                $bookingSession->timeText = $timeText;
                $bookingSession->timeValue = $timeValue;
                
                $bookingSession->bookingPrice = $price;
                $bookingSession->travelDuration = $timeText;
                
                $response->setStatusCode(201);
                $jsonModel->setVariables([
                    "price" => $price,
                    "time" => $timeText,
                    "pickup" => $dm->destination_addresses,
                    "destination" => $dm->origin_addresses,
                    "logo" => $this->url()
                        ->fromRoute('application', [
                        'action' => 'application'
                    ], [
                        'force_canonical' => true
                    ]) . "assets/img/logo.png"
                ]);
            }
        }
        
        return $jsonModel;
    }

    public function makepaymentAction()
    {
        $jsonModel = new JsonModel();
        $bookingSession = $this->bookingService->getBookingSession();
        $response = $this->getResponse();
        $flutterwaveService = $this->flutterwaveService;
        $response->setStatusCode(200);
        $jsonModel->setVariables([
            "price" => $bookingSession->bookingPrice,
            "txref" => FlutterwaveService::generateTransaction(),
            "public_key" => $flutterwaveService->getFlutterwavePublicKey(),
            "logo" => $this->url()
                ->fromRoute('application', [
                'action' => 'application'
            ], [
                'force_canonical' => true
            ]) . "assets/img/logo.png"
        ]);
        return $jsonModel;
    }

    /**
     * Use this to conclude booking after Payment
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function completebookingAction()
    {
        $flutterwaveService = $this->flutterwaveService;
        $bookingSession = $this->bookingService->getBookingSession();
        $em = $this->entityManager;
        $user = $this->identity();
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $txRef = $post["txRef"];
            $amountPayed = $post["amountPayed"];
            try {
                $verifyData = $flutterwaveService->setTxRef($txRef)->verifyPayment();
                // var_dump($verifyData);
                if ($verifyData->status == "success" && $verifyData->data->chargedamount >= $amountPayed) {
                    
                    $bookingEntity = $this->bookingService->createBooking();
                    // var_dump("After Booking");
                    $transactionEntity = $flutterwaveService->setAmountPayed($verifyData->data->chargedamount)
                        ->setTxRef($verifyData->data->txref)
                        ->setFlwId($verifyData->data->txid)
                        ->setFlwRef($verifyData->data->flwref)
                        ->setBooking($bookingEntity)
                        ->setSettledAmount($verifyData->data->amountsettledforthistransaction)
                        ->setTransactStatus(FlutterwaveService::TRANSACTION_STATUS_PAID)
                        ->setTransactUser($this->identity()
                        ->getId())
                        ->hydrateTransaction();
                    // var_dump("After Transaction");
                    $em->persist($bookingEntity);
                    $em->persist($transactionEntity);
                    
                    $em->flush();
                    // $flutterwaveService->setTransactionId($transactionEntity->getId());
                    // $flutterwaveService->initiateTrasnfer();
                    $response->SetStatusCode(201);
                    $this->flashmessenger()->addSuccessMessage("N{$verifyData->data->chargedamount} has been charged from your account and a request is processing");
                    $jsonModel->setVariables([
                        "data" => $verifyData->data->chargedamount
                    ]);
                    
                    // Notify Controller
                    $generalService = $this->generalService;
                    $pointer["to"] = GeneralService::COMPANY_EMAIL;
                    $pointer["fromName"] = "System Robot";
                    $pointer['subject'] = "New Booking";
                    
                    $template['template'] = "admin-new-booking";
                    $template["var"] = [
                        "logo" => $this->url()->fromRoute('application', [
                            'action' => 'application'
                        ], [
                            'force_canonical' => true
                        ]) . "assets/img/logo.png",
                        "bookingUid" => $transactionEntity->getBooking()->getBookingUid(),
                        "fullname" => $transactionEntity->getBooking()
                            ->getUser()
                            ->getFullName(),
                        "amount" => $transactionEntity->getAmount()
                    ];
                    $generalService->sendMails($pointer, $template);
                }
            } catch (\Exception $e) {
                $response->setStatusCode(400);
                $jsonModel->setVariables([
                    "message" => $e->getTrace(),
                    "data" => $verifyData
                ]);
            }
        }
        return $jsonModel;
    }

    public function cashpaymentAction()
    {
        $response = $this->getResponse();
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        $user = $this->identity();
        if ($request->isPost()) {
            $bookingSession = $this->bookingService->getBookingSession();
            try {
                
                $bookingEntity = $this->bookingService->createBooking();
                
                $em->persist($bookingEntity);
                
                $em->flush();
                
                $tripCode = $bookingEntity->getTripCode();
                $byPassCode = $bookingEntity->getByPassCode();
                
                // send email
                $generalService = $this->generalService;
                $pointers["to"] = "support@baucars.com";
                $pointers["fromName"] = "System Robot";
                $pointers['subject'] = "New Booking";
                
                $template['template'] = "admin-new-booking";
                $template["var"] = [
                    "logo" => $this->url()->fromRoute('application', [
                        'action' => 'application'
                    ], [
                        'force_canonical' => true
                    ]) . "assets/img/logo.png",
                    "bookingUid" => $bookingEntity->getBookingUid(),
                    "fullname" => $bookingEntity->getUser()->getFullName(),
                    "amount" => $bookingSession->bookingPrice,
                    "tripCode"=>$tripCode
                ];
                
               
                
                $generalService->sendMails($pointers, $template);
                
                $pointer["to"] = $user->getEmail();
                $pointer["fromName"] = "Your Bau Cars Booking";
                $pointer['subject'] = "My Booking";
                
                $template['template'] = "admin-new-booking";
                $template["var"] = [
                    "logo" => $this->url()->fromRoute('application', [
                        'action' => 'application'
                    ], [
                        'force_canonical' => true
                    ]) . "assets/img/logo.png",
                    "bookingUid" => $bookingEntity->getBookingUid(),
                    "fullname" => $bookingEntity->getUser()->getFullName(),
                    "amount" => $bookingSession->bookingPrice,
                    "tripCode"=>$tripCode
                ];
                
                $generalService->sendMails($pointer, $template);
                
                
                
                $this->flashmessenger()->addSuccessMessage("Your booking has beeen initiated ");
                $response->setStatusCode(201);
                // $this->flashmessenger()->addSuccessMessage("N{$verifyData->data->chargedamount} has been charged from your account and a request is processing");
                $jsonModel->setVariables([ // "data" => $verifyData->data->chargedamount
]);
            } catch (\Exception $e) {
                $response->setStatusCode(400);
                $jsonModel->setVariables([
                    "message" => $e->getTrace()
                    // "data" => $verifyData
                ]);
            }
        }
        return $jsonModel;
    }

    public function boardAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    /**
     *
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @return the $bookingService
     */
    public function getBookingService()
    {
        return $this->bookingService;
    }

    /**
     *
     * @param \General\Service\GeneralService $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityManager $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @param \Customer\Service\BookingService $bookingService            
     */
    public function setBookingService($bookingService)
    {
        $this->bookingService = $bookingService;
        return $this;
    }

    /**
     *
     * @return the $flutterwaveService
     */
    public function getFlutterwaveService()
    {
        return $this->flutterwaveService;
    }

    /**
     *
     * @param \General\Service\FlutterwaveService $flutterwaveService            
     */
    public function setFlutterwaveService($flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
        return $this;
    }
}

