<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use General\Service\GeneralService;
use Logistics\Entity\LogisticsRequest;
use Doctrine\ORM\Query;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Laminas\Paginator\Paginator;
use Logistics\Service\LogisticsService;
use Laminas\View\Model\JsonModel;
use Logistics\Entity\LogisticsRequestStatus;
use CsnUser\Entity\User;

// use Laminas\View\Model\JsonModel;

/**
 *
 * @author mac
 *        
 */
class LogisticsController extends AbstractActionController
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    /**
     *
     * @var LogisticsService
     */
    private $logisticsService;

    public function onDispatch(MvcEvent $e)
    {
        return parent::onDispatch($e); // TODO: Change the autogenerated stub
    }

    public function createdispatchAction()
    {
        return new ViewModel();
    }

    public function viewAction()
    {
        $viewModel = new ViewModel();
        $id = $this->params()->fromRoute("id", NULL);
        
        if ($id == NULL) {
            $viewModel->setVariables([
                "error" => "Absent Identifier"
            ]);
        } else {
            $em = $this->entityManager;
            $query = $em->getRepository(LogisticsRequest::class)
                ->createQueryBuilder("l")
                ->select([
                "l",
                "u",
                "pm",
                "svt",
                "t",
                "st",
                "ar",
                "aru"
            
            ])
                ->leftJoin("l.user", "u")
                ->leftJoin("l.serviceType", "svt")
                ->leftJoin("l.paymentmode", "pm")
                ->leftJoin("l.logisticsTransaction", "t")
                ->leftJoin("l.status", "st")
                ->leftjoin("l.assignedRider", "ar")
                ->leftjoin("ar.user", "aru")
                ->where("l.logisticsUid = :uid")
                ->setParameters([
                "uid" => $id
            ])
                ->getQuery()
                ->setHydrationMode(Query::HYDRATE_ARRAY)
                ->getArrayResult();
            
//             var_dump($query);
            $viewModel->setVariables([
                "data" => $query[0]
            ]);
        }
        
        return $viewModel;
    }

    /**
     * Removes dispatch entity changes status from active to inactive
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function removeDispatchAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post = $request->getPost();
                $id = $post['id'];
                /**
                 *
                 * @var LogisticsRequest $dispatchEntity
                 */
                $dispatchEntity = $em->find(LogisticsRequest::class, $id);
                $dispatchEntity->setIsActive(False)->setUpdatedOn(new \Datetime());
                // exit();
                
                $em->persist($dispatchEntity);
                $em->flush();
                $response->setStatusCode(204);
            } catch (\Exception $e) {
                // var_dump($e->getMessage());
            }
        }
        return $jsonModel;
    }

    /**
     * earches for user based on emai phoenumber or fullname
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function searchforuserAction()
    {
        $jsonModel = new JsonModel();
        $word = $this->params()->fromRoute("id", NULL);
        $em = $this->entityManager;
        $data = $em->getRepository(User::class)
            ->createQueryBuilder("l")
            ->select([
            "l"
        ])
            ->where('l.phoneNumber LIKE :word')
            ->orWhere('l.fullName LIKE :word')
            ->orWhere('l.email LIKE :word')
            ->setParameter('word', '%' . $word . '%')
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY)
            ->getArrayResult();
        
        $jsonModel->setVariables([
            "data" => $data
        ]);
        
        return $jsonModel;
    }

    public function createManualDispatchAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $em = $this->entityManager;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $logisticService = $this->logisticsService;
            try {
                // var_dump($post);
                $data = $logisticService->createRequest($post);
                
                $userEntity = $em->find(User::class, $post["user"]);
                $generalService = $this->generalService;
                $pointer["to"] = $userEntity->getEmail();
                $pointer["fromName"] = "Bau Dispatch";
                $pointer['subject'] = "Bau Dispatch Request";
                
                $template['template'] = "logistics_create_request";
                $template["var"] = [
                    // "amount" => $data["amountPaid"],
                    // "fullname" => $data["userFullname"],
                    "logo" => "https://baucars.com/img/baulog.png"
                    // "bookingUid" => $this->booking->getBookingUid()
                ];
                
                $generalService->sendMails($pointer, $template);
                
                $pointer["to"] = GeneralService::COMPANY_EMAIL;
                $pointer["fromName"] = "Bau Dispatch";
                $pointer['subject'] = "Bau Dispatch Request";
                
                $template['template'] = "logistics_create_request";
                $template["var"] = [
                    // "amount" => $data["amountPaid"],
                    // "fullname" => $data["userFullname"],
                    "logo" => "https://baucars.com/img/baulog.png"
                    // "bookingUid" => $this->booking->getBookingUid()
                ];
                
                $generalService->sendMails($pointer, $template);
                
                $response->setStatusCode(201);
            } catch (\Exception $e) {
                var_dump($e->geMessages());
            }
        }
        return $jsonModel;
    }

    public function dispatchdetailsAction()
    {
        $jsonModel = new JsonModel();
        
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->setStatusCode(401);
        
        if ($request->isPost()) {
            $post = $request->getPost();
            // var_dump($post);
            
            $logisticsService = $this->logisticsService;
            try {
                $data = $logisticsService->priceandDistanceCalculator($post);
                $jsonModel->setVariable("data", $data);
                $response->setStatusCode(200);
            } catch (\Exception $e) {
                $response->setStatusCode(400);
                $jsonModel->setVariables([
                    "error" => $e->getMessage()
                ]);
            }
        }
        return $jsonModel;
    }

    /**
     * This return a list of all
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $em = $this->entityManager;
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(LogisticsRequest::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "st",
            "u",
            "sta",
            "t"
        ])
            ->leftJoin("l.serviceType", "st")
            ->leftJoin("l.user", "u")
            ->leftJoin("l.logisticsTransaction", "t")
            ->leftJoin("l.status", "sta")
            ->orderBy("l.id", "DESC")
            ->where("l.isActive = :active")
            ->setParameters([
            "active" => true
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            "data" => $paginator
        ]);
        return $viewModel;
    }

    public function upcomingTripAction()
    {
        $em = $this->entityManager;
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(LogisticsRequest::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "st",
            "u",
            "sta",
            "t"
        ])
            ->leftJoin("l.serviceType", "st")
            ->leftJoin("l.user", "u")
            ->leftJoin("l.status", "sta")
            ->leftJoin("l.logisticsTransaction", "t")
            ->orderBy("l.id", "DESC")
            ->where("l.status = :status")
            ->setParameters([
            "status" => LogisticsService::LOGISTICS_STATUS_PROCESSING
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            "data" => $paginator
        ]);
        return $viewModel;
    }

    public function initiatedTripAction()
    {
        $em = $this->entityManager;
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(LogisticsRequest::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "st",
            "u",
            "t",
            "sta"
        ])
            ->leftJoin("l.serviceType", "st")
            ->leftJoin("l.user", "u")
            ->leftJoin("l.status", "sta")
            ->leftJoin("l.logisticsTransaction", "t")
            ->orderBy("l.id", "DESC")
            ->where("l.status = :status")
            ->andWhere("l.isActive = :active")
            ->setParameters([
            "status" => LogisticsService::LOGISTICS_STATUS_INITIATED,
            "active" => TRUE
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            "data" => $paginator
        ]);
        return $viewModel;
    }

    public function inTransitTripAction()
    {
        $em = $this->entityManager;
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(LogisticsRequest::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "st",
            "u",
            "sta",
            "t"
        ])
            ->leftJoin("l.serviceType", "st")
            ->leftJoin("l.user", "u")
            ->leftJoin("l.status", "sta")
            ->leftJoin("l.logisticsTransaction", "t")
            ->where("l.status = :status")
            ->andWhere("l.isActive = :active")
            ->setParameters([
            "status" => LogisticsService::LOGISTICS_STATUS_PROCESSING,
            "active" => TRUE
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            "data" => $paginator
        ]);
        return $viewModel;
    }

    public function cancelAction()
    {
        $em = $this->entityManager;
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(LogisticsRequest::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "st",
            "u",
            "sta",
            
            "t"
        ])
            ->leftJoin("l.serviceType", "st")
            ->leftJoin("l.user", "u")
            ->leftJoin("l.logisticsTransaction", "t")
            ->leftJoin("l.status", "sta")
            ->where("l.status = :status")
            ->andWhere("l.isActive = :active")
            ->setParameters([
            "status" => LogisticsService::LOGISTICS_STATUS_CANCELED,
            "active" => TRUE
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            "data" => $paginator
        ]);
        return $viewModel;
    }

    public function deliveredAction()
    {
        $em = $this->entityManager;
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(LogisticsRequest::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "st",
            "u",
            "sta",
            "t"
        ])
            ->leftJoin("l.serviceType", "st")
            ->leftJoin("l.user", "u")
            ->leftJoin("l.logisticsTransaction", "t")
            ->leftJoin("l.status", "sta")
            ->where("l.status = :status")
            ->andWhere("l.isActive = :active")
            ->setParameters([
            "status" => LogisticsService::LOGISTICS_STATUS_DELIVERED,
            "active" => TRUE
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            "data" => $paginator
        ]);
        return $viewModel;
    }

    public function getridersAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $data = $em->createQueryBuilder("r")
            ->select([
            "r"
        ])
            ->where("r.isActive = :active")
            ->setParameters([
            "active" => TRUE
        ])
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY)
            ->getArrayResult();
        
        $jsonModel->setVariables([
            "data" => $data
        ]);
        return $jsonModel;
    }

    /**
     * Changes the state of the Dispact to delivered
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function delieveredDispatchAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post = $request->getPost();
            // var_dump($post);
            /**
             *
             * @var LogisticsRequest $dispatchEntity
             */
            $dispatchEntity = $em->find(LogisticsRequest::class, $post["id"]);
            $dispatchEntity->setStatus($em->find(LogisticsRequestStatus::class, LogisticsService::LOGISTICS_STATUS_DELIVERED))
                ->setUpdatedOn(new \Datetime());
            $em->persist($dispatchEntity);
            $em->flush();
            
            // Send Email here
            // TO-DO set rider free
            $response->setStatusCode(201);
            $this->flashMessenger()->addSuccessMessage("The Customer has been notified of the package delivery status");
        }
        return $jsonModel;
    }

    /**
     * Changes the state of the dispatch to canceled
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function canceldDispatcthAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post = $request->getPost();
            /**
             *
             * @var LogisticsRequest $dispatchEntity
             */
            $dispatchEntity = $em->find(LogisticsRequest::class, $post["id"]);
            $dispatchEntity->setStatus($em->find(LogisticsRequestStatus::class, LogisticsService::LOGISTICS_STATUS_CANCELED))
                ->setUpdatedOn(new \Datetime());
            
            // TO-DO set rider free
            $em->persist($dispatchEntity);
            $em->flush();
            
            // Send Email here
            
            $response->setStatusCode(202);
            $this->flashMessenger()->addSuccessMessage("The dispatch request has been successfully cancelled");
        }
        return $jsonModel;
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
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
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
     * @param \General\Service\GeneralService $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     *
     * @return the $logisticsService
     */
    public function getLogisticsService()
    {
        return $this->logisticsService;
    }

    /**
     *
     * @param \Logistics\Service\LogisticsService $logisticsService            
     */
    public function setLogisticsService($logisticsService)
    {
        $this->logisticsService = $logisticsService;
        return $this;
    }
}

