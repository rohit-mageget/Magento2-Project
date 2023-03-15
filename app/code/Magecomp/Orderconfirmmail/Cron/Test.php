<?php

namespace Magecomp\Orderconfirmmail\Cron;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

use \Psr\Log\LoggerInterface;

class Test {

  protected $logger;

  protected $transportBuilder;
  protected $storeManager;
  protected $inlineTranslation;

  public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state
    
    ) {

    $this->logger = $logger;
    $this->transportBuilder = $transportBuilder;
    $this->storeManager = $storeManager;
    $this->inlineTranslation = $state;

  }

  /**

    * Write to system.log

    *

    * @return void

  */

  public function execute() {

    // Do your Stuff

    $this->logger->info('Cron Works sussefully');
    $this->sendEmail();

  }

  public function sendEmail()
    {
        // this is an example and you can change template id,fromEmail,toEmail,etc as per your need.
        $templateId = 5; // template id
        $fromEmail = 'rohit.mageget@gmail.com';  // sender Email id
        $fromName = 'Rohit';             // sender Name
        $toEmail = 'deepakmageget@gmail.com'; // receiver email id

        try {
            // template variables pass here
            $templateVars = [
                'name' => '<span style = "color:blue">Rohit Mishra</span>',
                'email' =>'<span style = "color:blue">rohit.mageget@gmail.com</span>',
                'mob' =>'<span style = "color:red">6392375487</span>',
                'message' =>'<span style = "color:blue">this is an example and you can change template id,fromEmail,toEmail,etc as per your need.</span>'
            ];

            $storeId = $this->storeManager->getStore()->getId();

            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }

}
