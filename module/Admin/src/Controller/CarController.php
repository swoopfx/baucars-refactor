<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Paginator\CarAdapater;
use Laminas\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Cars;
use Doctrine\ORM\Query;
use Laminas\Mvc\MvcEvent;
use Laminas\InputFilter\InputFilter;

/**
 *
 * @author otaba
 *        
 */
class CarController extends AbstractActionController
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var CarAdapater
     */
    private $allCarsPaginator;

    private $generalService;

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

    public function indexAction()
    {
        $allCArs = $this->allCarsPaginator;
        $viewModel = new ViewModel([
            "cars" => $allCArs
        ]);
        return $viewModel;
    }

    public function getCarAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $id = $this->params()->fromRoute("id", NULL);
        
        $repo = $em->getRepository(Cars::class);
        
        $car = $repo->createQueryBuilder("r")
            ->select("r, mk, mc, mt")
            ->where("r.carUid = :carUid")
            ->leftJoin("r.motorMake", "mk")
            ->leftJoin("r.motorType", "mt")
            ->leftJoin("r.motorClass", "mc")
            ->setParameters([
            "carUid" => $id
        ])
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        
        $response->setStatusCode(200);
        $jsonModel = new JsonModel([
            "car" => $car[0]
        ]);
        
        return $jsonModel;
    }
    
    public function postEditAction(){
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $request = $this->getRequest();
        if($request->isPost()){
            $post = $request->getPost()->toArray();
            
            $inputFilter = new InputFilter();
            if($inputFilter->isValid()){
                $data = $inputFilter->getValues();
                
            }
        }
        $response = $this->getResponse();
        return $jsonModel;
    }

    /**
     *
     * @return the $allCarsPaginator
     */
    public function getAllCarsPaginator()
    {
        return $this->allCarsPaginator;
    }

    /**
     *
     * @param field_type $allCarsPaginator            
     */
    public function setAllCarsPaginator($allCarsPaginator)
    {
        $this->allCarsPaginator = $allCarsPaginator;
        return $this;
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
     * @param field_type $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
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
     * @param \Doctrine\ORM\EntityManager $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}

