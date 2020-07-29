<?php
namespace HTCMage\ProductAttachment\Ui\Component\Listing\Column;

class CustomerGroup implements \Magento\Framework\Option\ArrayInterface
{
   private $groupCollection;

    public function __construct(
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollection
    ) {
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
