<?php
/**
 * Created by PhpStorm.
 * User: Bharat-Magecomp
 * Date: 8/29/2019
 * Time: 9:24 AM
 */

namespace Magecomp\Orderconfirmmail\Plugin\Sales\Order\Email\Container;


class OrderIdentityPlugin
{
    /**
     * @var \Magento\Checkout\Model\Session $checkoutSession
     */
    protected $checkoutSession;
    protected $helperData;
    protected $request;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Request\Http $request,
        \Magecomp\Orderconfirmmail\Helper\Data $helperData
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->request = $request;
        $this->helperData = $helperData;
    }

    /**
     * @param \Magento\Sales\Model\Order\Email\Container\OrderIdentity $subject
     * @param callable $proceed
     * @return bool
     */
    public function aroundIsEnabled(\Magento\Sales\Model\Order\Email\Container\OrderIdentity $subject, callable $proceed)
    {
        if($this->request->getRouteName() == 'checkout' &&
            $this->request->getControllerName() == 'onepage' &&
            $this->request->getActionName() == 'success') {
            return true;
        }
        $returnValue = $proceed();
        if($this->helperData->isEnabled()) {
            $returnValue = false;
            $forceOrderMailSentOnSuccess = $this->checkoutSession->getForceOrderMailSentOnSuccess();
            if (isset($forceOrderMailSentOnSuccess) && $forceOrderMailSentOnSuccess) {
                $returnValue = true;
                $this->checkoutSession->unsForceOrderMailSentOnSuccess();
            }
        }
        return $returnValue;
    }
}