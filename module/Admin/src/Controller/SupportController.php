<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use General\Service\GeneralService;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Application\Entity\Support;

/**
 *
 * @author otaba
 *        
 */
class SupportController extends AbstractActionController
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

    // TODO - Insert your code here
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function indexAction()
    {
        $em = $this->entityManager;
        $repo = $em->getRepository(Support::class);
        //Paginated List
//         $data = $repo->createQueryBuilder("t")
//             ->orderBy("t.updatedOn", "desc")
//             ->getQuery()
//             ->getResult();
        $viewModel = new ViewModel([
            "data" => $data
        ]);
        return $viewModel;
    }

    public function getTicketsjsonAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        
        $response->setStatusCode(200);
        return $jsonModel;
    }

    public function getMessagesJsonAction()
    {
        $jsonModel = new JsonModel();
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
}

