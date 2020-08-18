<?php

namespace HTCMage\ProductAttachment\Ui\Component\Listing\Column;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Type
 * @package HTCMage\ProductAttachment\Ui\Component\Listing\Column
 */
class Type implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('File upload')], ['value' => 1, 'label' => __('URL File')]];
    }
}
