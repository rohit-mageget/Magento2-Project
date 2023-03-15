<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);
namespace Dww\Quote\Model\Magento\Sales\Order\Pdf\Items\Invoice;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\RtlTextHandler;
/**
 * Sales Order Invoice Pdf default items renderer
 */
class DefaultInvoice extends \Magento\Sales\Model\Order\Pdf\Items\AbstractItems
{
    /**
     * Core string
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;
    /**
     * @var ItemFactory
     */
    public $ItemFactory;
    /**
     * @var RtlTextHandler
     */
    private $rtlTextHandler;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Tax\Helper\Data $taxData
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @param RtlTextHandler|null $rtlTextHandler
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Sales\Model\Order\ItemFactory $ItemFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        ?RtlTextHandler $rtlTextHandler = null
    ) {
        $this->string = $string;
        $this->ItemFactory = $ItemFactory;
        $this->json = $json;
        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $resource,
            $resourceCollection,
            $data
        );
        $this->rtlTextHandler =
            $rtlTextHandler ?:
            ObjectManager::getInstance()->get(RtlTextHandler::class);
    }
    public function excute()
    {
        $order = $this->getOrder();
        
        $custom = $this->ItemFactory->create()->load($order->getId());
        return $custom['customized_parts'];
    
    }
    /**
     * Draw item line
     *
     * @return void
     */
    public function draw()
    {
        $order = $this->getOrder();
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];

        $customizedImage = $this->excute();
        $abc =  [$item->getName()];
        if (isset($customizedImage) && !empty($customizedImage)) {
            $jsonDecode = $this->json->unserialize($customizedImage);
           
            $final = "Customized Part:-";
            $abc [] = $final;

            $top = 390;
            $bottom = 400;
            $left = 80;
            $right =150;
            $finalarray = [];
            foreach ($jsonDecode as $data => $value) {
                $hex = $value;
                list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
                $color="rgb($r,$g,$b)";
                
               $page->setFillColor(new \Zend_Pdf_Color_RGB((int)$r,(int)$g,(int)$b));
               $page->setFillColor(new \Zend_Pdf_Color_Html($value));
   
               $page->drawRectangle($left, $bottom, $right, $top);
               $top-=22;
               $bottom=$top+10;

               $abc [] = $data . ": ";

            }

        } else {
            $final = "";
        }
       
        // draw Product name

        $lines[0][] = ["text" => $abc, "feed" => 35];

        // draw SKU
        $lines[0][] = [
            "text" => $this->string->split(
                $this->prepareText((string) $this->getSku($item)),
                17
            ),
            "feed" => 290,
            "align" => "right",
        ];
        // draw QTY
        $lines[0][] = [
            "text" => $item->getQty() * 1,
            "feed" => 435,
            "align" => "right",
        ];
        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
        $feedPrice = 395;
        $feedSubtotal = $feedPrice + 170;
        foreach ($prices as $priceData) {
            if (isset($priceData["label"])) {
                // draw Price label
                $lines[$i][] = [
                    "text" => $priceData["label"],
                    "feed" => $feedPrice,
                    "align" => "right",
                ];
                // draw Subtotal label
                $lines[$i][] = [
                    "text" => $priceData["label"],
                    "feed" => $feedSubtotal,
                    "align" => "right",
                ];
                $i++;
            }
            // draw Price
            $lines[$i][] = [
                "text" => $priceData["price"],
                "feed" => $feedPrice,
                "font" => "bold",
                "align" => "right",
            ];
            // draw Subtotal
            $lines[$i][] = [
                "text" => $priceData["subtotal"],
                "feed" => $feedSubtotal,
                "font" => "bold",
                "align" => "right",
            ];
            $i++;
        }
        // draw Tax
        $lines[0][] = [
            "text" => $order->formatPriceTxt($item->getTaxAmount()),
            "feed" => 495,
            "font" => "bold",
            "align" => "right",
        ];
        // custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    "text" => $this->string->split(
                        $this->filterManager->stripTags($option["label"]),
                        40,
                        true,
                        true
                    ),
                    "font" => "italic",
                    "feed" => 35,
                ];
                // Checking whether option value is not null
                if ($option["value"] !== null) {
                    if (isset($option["print_value"])) {
                        $printValue = $option["print_value"];
                    } else {
                        $printValue = $this->filterManager->stripTags(
                            $option["value"]
                        );
                    }
                    $values = explode(", ", $printValue);
                    foreach ($values as $value) {
                        $lines[][] = [
                            "text" => $this->string->split(
                                $value,
                                30,
                                true,
                                true
                            ),
                            "feed" => 40,
                        ];
                    }
                }
            }
        }
        $lineBlock = ["lines" => $lines, "height" => 20];
        $page = $pdf->drawLineBlocks(
            $page,
            [$lineBlock],
            ["table_header" => true]
        );
        $this->setPage($page);
    }
    /**
     * Returns prepared for PDF text, reversed in case of RTL text
     *
     * @param string $string
     * @return string
     */
    private function prepareText(string $string): string
    {
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        return $this->rtlTextHandler->reverseRtlText(
            html_entity_decode($string)
        );
    }
}
