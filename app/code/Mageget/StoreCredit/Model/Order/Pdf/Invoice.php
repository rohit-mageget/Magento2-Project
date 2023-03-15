<?php


// protected function insertOrderCustom(&$page, $obj, $putOrderId = true)
// {
//     if ($obj instanceof \Magento\Sales\Model\Order) {
//         $shipment = null;
//         $order = $obj;
//     } elseif ($obj instanceof \Magento\Sales\Model\Order\Shipment) {
//         $shipment = $obj;
//         $order = $shipment->getOrder();
//     }

//     $this->y = $this->y ? $this->y : 815;
//     $top = $this->y;

//     $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0.45));
//     $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.45));
//     $page->drawRectangle(25, $top, 570, $top - 65);
//     $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
//     $this->setDocHeaderCoordinates([25, $top, 570, $top - 55]);
//     $this->_setFontRegular($page, 10);

//     if ($putOrderId) {
//         $page->drawText(__('Order # ') . $order->getRealOrderId(), 35, $top -= 30, 'UTF-8');
//     }
//     $page->drawText(
//         __('Order Date: ') .
//         $this->_localeDate->formatDate(
//             $this->_localeDate->scopeDate(
//                 $order->getStore(),
//                 $order->getCreatedAt(),
//                 true
//             ),
//             \IntlDateFormatter::MEDIUM,
//             false
//         ),
//         35,
//         $top -= 15,
//         'UTF-8'
//     );
//     $gstin = $this->_scopeConfig->getValue(
//     "gst/codilar/gstin",
//     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
//     );
//     if (strlen($gstin)>0) {
//         //$page->drawText(__('GSTIN : ') . $gstin, 35, $top -= 15, 'UTF-8');
//     }

//     $top -= 10;
//     $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
//     $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
//     $page->setLineWidth(0.5);
//     $page->drawRectangle(25, $top, 275, $top - 25);
//     $page->drawRectangle(275, $top, 570, $top - 25);

//     /* Calculate blocks info */

//     /* Billing Address */
//     $billingAddress = $this->_formatAddress($this->addressRenderer->format($order->getBillingAddress(), 'pdf'));
//     $gstin = 'GSTIN: '.$order->getBillingAddress()->getData('gstin');
//         if($gstin != null)
//         {
//             array_push($billingAddress,$gstin);
//         }

//     /* Payment */
//     $paymentInfo = $this->_paymentData->getInfoBlock($order->getPayment())->setIsSecureMode(true)->toPdf();
//     $paymentInfo = htmlspecialchars_decode($paymentInfo, ENT_QUOTES);
//     $payment = explode('{{pdf_row_separator}}', $paymentInfo);
//     foreach ($payment as $key => $value) {
//         if (strip_tags(trim($value)) == '') {
//             unset($payment[$key]);
//         }
//     }
//     reset($payment);

//     /* Shipping Address and Method */
//     if (!$order->getIsVirtual()) {
//         /* Shipping Address */
//         $shippingAddress = $this->_formatAddress($this->addressRenderer->format($order->getShippingAddress(), 'pdf'));
//         $shippingMethod = $order->getShippingDescription();
//     }

//     $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
//     $this->_setFontBold($page, 12);
//     $page->drawText(__('Sold to:'), 35, $top - 15, 'UTF-8');

//     if (!$order->getIsVirtual()) {
//         $page->drawText(__('Ship to:'), 285, $top - 15, 'UTF-8');
//     } else {
//         $page->drawText(__('Payment Method:'), 285, $top - 15, 'UTF-8');
//     }

//     $addressesHeight = $this->_calcAddressHeight($billingAddress);
//     if (isset($shippingAddress)) {
//         $addressesHeight = max($addressesHeight, $this->_calcAddressHeight($shippingAddress));
//     }

//     $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
//     $page->drawRectangle(25, $top - 25, 570, $top - 33 - $addressesHeight);
//     $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
//     $this->_setFontRegular($page, 10);
//     $this->y = $top - 40;
//     $addressesStartY = $this->y;

//     foreach ($billingAddress as $value) {
//         if ($value !== '') {
//             $text = [];
//             foreach ($this->string->split($value, 40, true, true) as $_value) {
//                 $text[] = $_value;
//             }
//             foreach ($text as $part) {
//                 $page->drawText(strip_tags(ltrim($part)), 35, $this->y, 'UTF-8');
//                 $this->y -= 15;
//             }
//         }
//     }

//     $addressesEndY = $this->y;

//     if (!$order->getIsVirtual()) {
//         $this->y = $addressesStartY;
//         foreach ($shippingAddress as $value) {
//             if ($value !== '') {
//                 $text = [];
//                 foreach ($this->string->split($value, 40, true, true) as $_value) {
//                     $text[] = $_value;
//                 }
//                 foreach ($text as $part) {
//                     $page->drawText(strip_tags(ltrim($part)), 285, $this->y, 'UTF-8');
//                     $this->y -= 15;
//                 }
//             }
//         }

//         $addressesEndY = min($addressesEndY, $this->y);
//         $this->y = $addressesEndY;


//         $this->y -= 15;
//         $this->_setFontBold($page, 9.5);
//         $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
//         $page->drawText(__('Payment Method :'), 300, $top + 40, 'UTF-8');
//         $page->drawText(__('Shipping Method :'), 300,$top + 25, 'UTF-8');

//         $this->y -= 10;
//         $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));

//         $this->_setFontRegular($page, 10);
//         $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));

//         $paymentLeft = 35;
//         $yPayments = $this->y - 15;
//     } else {
//         $yPayments = $addressesStartY;
//         $paymentLeft = 285;
//     }

//     foreach ($payment as $value) {
//         if (trim($value) != '') {
//             //Printing "Payment Method" lines
//             $value = preg_replace('/<br[^>]*>/i', "\n", $value);
//             foreach ($this->string->split($value, 45, true, true) as $_value) {
//                 $page->drawText(strip_tags(trim($_value)), 400, $top + 40, 'UTF-8');
//                 $yPayments -= 15;
//             }
//         }
//     }

//     if ($order->getIsVirtual()) {
//         // replacement of Shipments-Payments rectangle block
//         // $yPayments = min($addressesEndY, $yPayments);
//         // $page->drawLine(25, $top - 25, 25, $yPayments);
//         // $page->drawLine(570, $top - 25, 570, $yPayments);
//         // $page->drawLine(25, $yPayments, 570, $yPayments);

//         $this->y = $yPayments - 15;
//     } else {
//         $topMargin = 15;
//         $methodStartY = $this->y;
//         $this->y -= 15;

//         foreach ($this->string->split($shippingMethod, 45, true, true) as $_value) {
//             $page->drawText(strip_tags(trim($_value)),  400, $top + 25, 'UTF-8');
//             $this->y -= 15;
//         }

//         $yShipments = $this->y;
//         $totalShippingChargesText = "(" . __(
//                 'Total Shipping Charges'
//             ) . " " . $order->formatPriceTxt(
//                 $order->getShippingAmount()
//             ) . ")";

//         $page->drawText($totalShippingChargesText, 400, $top + 12, 'UTF-8');
//         $yShipments -= $topMargin + 10;


//         $this->y -= -45;
//     }
// } 