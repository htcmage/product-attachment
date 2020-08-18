<?php

namespace HTCMage\ProductAttachment\Ui\Component\Listing\Column;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Display
 * @package HTCMage\ProductAttachment\Ui\Component\Listing\Column
 */
class Display implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('On tab information')],
            ['value' => 1, 'label' => __('On footer')],
            ['value' => 2, 'label' => __('Under add to cart button')]];
    }
}
