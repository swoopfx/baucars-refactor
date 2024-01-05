<?php
namespace CsnUser\Form;

use Laminas\Form\Form;

/**
 *
 * @author swoopfx
 *        
 */
class LoginForm extends Form
{
    public function init(){
       
        $this->setAttributes(array(
            'action'=>'',
            'method'=>'POST',
            "autocomplete"=>"off"
        ));
        $this->addFields();
        $this->addCommon();
    }
    
    public function addFields(){
        $this->add(array(
            'name'=>'userBasicField',
            'type'=>'CsnUser\Form\Fieldset\UserBasicFieldset',
            'options'=>array(
                'use_as_base_fieldset'=>true
            )
        ));
        
        $this->add(array(
            'name'=>'securityQuestion',
            'type'=>'',
        ));
    }
    
    public function addCommon(){
        $this->form->add(array(
            'name' => 'csrf',
            'type' => 'Laminas\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));
        
       
        
        $this->form->add(array(
            'name' => 'submit',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => array(
                'type' => 'submit'
            )
        ));
    }
}

