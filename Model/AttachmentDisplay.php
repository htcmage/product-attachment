<?php
/**
 * *
 *  *
 *  *  NOTICE OF LICENSE
 *  *   @author HTCMage Team
 *  *   @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 *  *   @package HTCMage_ProductAttachment
 *  *
 *
 */

namespace HTCMage\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class AttachmentDisplay
 * @package HTCMage\ProductAttachment\Model
 */
class AttachmentDisplay extends AbstractModel
{
    /**
     *
     */
    const CACHE_TAG = 'htcmage_productattachment_store_view';
    /**
     * @var string
     */
    protected $_cacheTag = 'htcmage_productattachment_store_view';
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ResourceModel\AttachmentDisplay');
    }
}
