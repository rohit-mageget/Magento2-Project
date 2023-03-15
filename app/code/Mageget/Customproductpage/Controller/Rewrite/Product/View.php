<?php

    namespace Mageget\Customproductpage\Controller\Rewrite\Product;
    use Magento\Framework\App\Action\Context;
    use Magento\Framework\View\Result\PageFactory;
    class View extends \Magento\Catalog\Controller\Product\View
    {
        /**
         * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
         */

        Protected $customerSession;

        public function __construct(
            Context $context,
            \Magento\Catalog\Helper\Product\View $viewHelper,
            \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
            \Magento\Customer\Model\Session $customerSession,
            PageFactory $resultPageFactory
        ) {
            $this->viewHelper = $viewHelper;
            $this->resultForwardFactory = $resultForwardFactory;
            $this->resultPageFactory = $resultPageFactory;
            $this->customerSession=$customerSession;
            parent::__construct($context, $viewHelper, $resultForwardFactory,$resultPageFactory);

        }

        public function execute()
        {

            try{
                    // Get initial data from request
                    $categoryId = (int) $this->getRequest()->getParam('category', false);
                    $productId = (int) $this->getRequest()->getParam('id');
                    $specifyOptions = $this->getRequest()->getParam('options');

                    if ($this->getRequest()->isPost() && $this->getRequest()->getParam(self::PARAM_NAME_URL_ENCODED)) {
                        $product = $this->_initProduct();
                        if (!$product) {
                            return $this->noProductRedirect();
                        }
                        if ($specifyOptions) {
                            $notice = $product->getTypeInstance()->getSpecifyOptionMessage();
                            $this->messageManager->addNotice($notice);
                        }
                        if ($this->getRequest()->isAjax()) {
                            $this->getResponse()->representJson(
                                $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode([
                                    'backUrl' => $this->_redirect->getRedirectUrl()
                                ])
                            );
                            return;
                        }
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $resultRedirect->setRefererOrBaseUrl();
                        return $resultRedirect;
                    }

                    // Prepare helper and params
                    $params = new \Magento\Framework\DataObject();
                    $params->setCategoryId($categoryId);
                    $params->setSpecifyOptions($specifyOptions);

                    // Render page
                    try {
                        $page = $this->resultPageFactory->create();
                        $page->addHandle('customproductpage_index_view');
                        $this->viewHelper->prepareAndRender($page, $productId, $this, $params);
                        return $page;
                    } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                        return $this->noProductRedirect();
                    } catch (\Exception $e) {
                        $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                        $resultForward = $this->resultForwardFactory->create();
                        $resultForward->forward('noroute');
                        return $resultForward;
                    }

            }catch(Exception $e){

            }
            return parent::execute();
        }
    }