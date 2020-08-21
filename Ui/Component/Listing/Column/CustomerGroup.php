<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Ui\Component\Listing\Column;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class CustomerGroup
 * @package HTCMage\ProductAttachment\Ui\Component\Listing\Column
 */
class CustomerGroup implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    private $groupCollection;

    /**
     * CustomerGroup constructor.
     * @param CollectionFactory $groupCollection
     */
    public function __construct(
        CollectionFactory $groupCollection
    )
    {
        $this->groupCollection = $groupCollection;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $groupArr = [];
        $groups = $this->groupCollection->create();

        foreach ($groups as $group) {
            $groupArr[] = ['value' => $group->getData('customer_group_id'), 'label' => __($group->getData('customer_group_code'))];
        }

        return $groupArr;
    }
}
