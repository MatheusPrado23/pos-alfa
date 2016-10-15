<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;


class LoginController extends AbstractActionController {

    public $tableGateway;

//    public function __construct($tableGateway)
//    {
//        $this->tableGateway = $tableGateway;
//        
//    }

//    public function indexActionn() {
//        $key = 'CachedLogins';
//        $logins = $this->tableGateway->select()->toArray();
//        return new ViewModel(['logins' => $logins]);
//        
//    }

    private function getForm()
    {
        $form = new \Application\Form\CreateLogin;
        foreach ($form->getElements() as $element) {
            if (! $element instanceof \Zend\Form\Element\Submit) {
                $element->setAttributes([
                    'class' => 'form-control'
                ]);
            }
        }
        return $form;
    }
    
    public function indexAction() {        
//        $key = 'CachedLogins';
//        $logins = $this->tableGateway->select()->toArray();
        
        $request = $this->getRequest();
        $form = new \Application\Form\Login();
        $view = new ViewModel();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $dadosForm = $form->getData();
                $adapter = $this->getEvent()->getApplication()->getServiceManager()->get(\Application\Factory\DbAdapter::class);
                $auth = new \Application\Service\Auth($adapter, $dadosForm['usuario'], $dadosForm['senha']);
                if ($auth->authenticate()->isValid()) {
                    $sessao = new Container('Auth');
                    $sessao->admin = true;
                    $sessao->identity = $auth->authenticate()->getIdentity();
                    return $this->redirect()->toRoute('beer');
                } else {
                    $errorCode = $auth->authenticate()->getCode();
                    switch ($errorCode) {
                        case \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID:
                            $view->setVariable('error', "Pass not found!");
                            break;
                        case \Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND:
                            $view->setVariable('error', "User not found!");
                            break;
                        case \Zend\Authentication\Result::FAILURE_IDENTITY_AMBIGUOUS:
                            $view->setVariable('error', "User ambigous!");
                            break;
                        default :
                            $view->setVariable('error', "Failure identification, contact the admin!");
                            break;
                    }
                }
            } else {
                $view->setVariable('error', "   !");
            }
        }
        $view->setTerminal(true);
        return $view->setVariable('form', $form);
//        return $view(['logins' => $logins]);

    }
//    
//
//    public function createAction()
//    {
//        $form = $this->getForm();
//
//        $form->setAttribute('action', '/login/create');
//        $request = $this->getRequest();
//         /* se a requisição é post os dados foram enviados via formulário*/
//        if ($request->isPost()) {
//            $login = new \Application\Model\Login();
//            /* configura a validação do formulário com os filtros e validators da entidade*/
//            $form->setInputFilter($login->getInputFilter());
//            /* preenche o formulário com os dados que o usuário digitou na tela*/
//            $form->setData($request->getPost());
//            /* faz a validação do formulário*/
//            if ($form->isValid()) {
//                /* pega os dados validados e filtrados */
//                $data = $form->getData();
//                unset($data['send']);
//                /* salva a cerveja*/
//                $this->tableGateway->insert($data);
//                //$this->cache->removeItem('CachedBeers');
//                /* redireciona para a página inicial que mostra todas as cervejas*/
//                return $this->redirect()->toUrl('/login');
//            }
//        }
//        $view = new ViewModel(['form' => $form]);
//        $view->setTemplate('application/login/save.phtml');
//
//    return $view;
//
//    }

    public function sairAction() {
        $sessao = new Container;
        $sessao->getManager()->getStorage()->clear();
        return $this->redirect()->toRoute('login');
    }

}
