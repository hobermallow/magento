<?php
namespace Syrus\Remotelogin\Controller\Remotelogin;

class Login extends \Magento\Framework\App\Action\Action
{
  protected $accountManagement;
  protected $session;

  public function __construct(
\Magento\Framework\App\Action\Context $context,
\Magento\Customer\Model\AccountManagement $acm,
\Magento\Customer\Model\Session $ses,
\Magento\Framework\App\ResponseFactory $responseFactory
)
  {
    $this->accountManagement = $acm;
    $this->session = $ses;
    $this->_responseFactory = $responseFactory;
    $this->_url = $context->getUrl();
    return parent::__construct($context);
  }

  public function execute()
  {
        //se la richiesta avviene in post
        if($_SERVER['REQUEST_METHOD'] == "GET") {
                //recupero i campi della richiesta
                $username = $_GET['username'];
                $password = $_GET['password'];
                //autentico l'utente
                try {
                        $customer = $this->accountManagement->authenticate($username, $password);
                }
                catch(Exception $e) {
                        echo "Credenziali errate";
                        return;
                }
                //log in del cliente
                $this->session->loginById($customer->getId());
                $CustomRedirectionUrl = $this->_url->getUrl('?ckt=1');
                $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();

                exit;
        }
    }
}
