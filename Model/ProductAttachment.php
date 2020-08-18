<?php

namespace HTCMage\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class ProductAttachment
 * @package HTCMage\ProductAttachment\Model
 */
class ProductAttachment extends AbstractModel
{
    /**
     *
     */
    const CACHE_TAG = 'htcmage_productattachment_env';
    /**
     * @var string
     */
    protected $_cacheTag = 'htcmage_productattachment_env';
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ResourceModel\ProductAttachment');
    }
}
