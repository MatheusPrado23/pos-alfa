<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class CreateLogin extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add([
            'name' => 'usuario',
            'options' => [ 
                  'label' => 'User name',
            ],
            'type'  => 'Text',
        ]);

        $this->add([
            'name' => 'senha',
            'options' => [
                'label' => 'Pass',
            ],
            'type'  => 'Text',
        ]);

        $this->add([
            'name' => 'send',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Create',
            ],
        ]);

        $this->setAttribute('action', '/login/save');
        $this->setAttribute('method', 'post');
    }
}
