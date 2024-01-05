<?php
namespace CsnUser\Form\Fieldset;

use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Form\Fieldset;

/**
 *
 * @author swoopfx
 *        
 */
class UserSecurityQuestionFieldset extends Fieldset implements InputFilterProviderInterface
{
    
    private $generalService;
    
    private $entityManager;


    public function init(){
        
        $this->addFields();
        
    }
    
    private function addFields(){
        $this->add(array(
            'name'=>'question',
            'type'=>'DoctrineModule\Form\Element\ObjectSelect',
            'options'=>array(
                'label' => 'Security Question:',
                'label_attributes' => array(
                    'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
                ),
                'object_manager' => $this->entityManager,
                'target_class' => 'CsnUser\Entity\Question',
                'property' => 'question',
                'empty_option' => '--- Select Security Question ---',
                 
        
            ),
            'attributes'=>array(),
        ));
        $this->add(array(
            'name'=>'answer',
            'type'=>'text',
            'options'=>array(
                'label' => 'Security Question:',
                'label_attributes' => array(
                    'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
                ),
            ),
            'attributes'=>array(
                'class'=>'form-control col-md-9 col-xs-12',
                'id' => 'security_answer',
                'required' => 'required',
            ),
        ));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\InputFilter\InputFilterProviderInterface::getInputFilterSpecification()
     *
     */
    public function getInputFilterSpecification()
    {
        
       return array();
    }
    
    public function setGeneralService($xserv){
        $this->generalService = $xserv;
        return $this;
    }
    
    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }
}

