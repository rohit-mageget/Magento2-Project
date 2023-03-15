<?php

namespace Mageget\OfflinePayment\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;


class ConfigOption implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'Bank of India', 'label' => __('Bank of India')],
            ['value' => 'Bank of Maharashtra', 'label' => __('Bank of Maharashtra')],
            ['value' => 'Canara Bank', 'label' => __('Canara Bank')],
            ['value' => 'Central Bank of India', 'label' => __('Central Bank of India')],
            ['value' => 'Indian Bank', 'label' => __('Indian Bank')],
            ['value' => 'Indian Overseas Bank', 'label' => __('Indian Overseas Bank')],
            ['value' => 'Punjab National Bank', 'label' => __('Punjab National Bank')],
            ['value' => 'State Bank of India', 'label' => __('State Bank of India')],
            ['value' => 'UCO Bank', 'label' => __('UCO Bank')],
            ['value' => 'Union Bank of India', 'label' => __('Union Bank of India')]
        ];
    }
}
