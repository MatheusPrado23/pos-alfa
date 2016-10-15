<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class Login extends Form {

    public function __construct() {
        parent::__construct();
        $this->add([
            'name' => 'usuario',
            'attributes' => [
                'required' => true
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'type' => 'Text',
        ]);

        $this->add([
            'name' => 'senha',
            'type' => 'Password',
            'attributes' => [
                'required' => true
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'send',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Login',
            ],
        ]);

        $this->setAttribute('action', '/login');
        $this->setAttribute('method', 'post');
    }

}
