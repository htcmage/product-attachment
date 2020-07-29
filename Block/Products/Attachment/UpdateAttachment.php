<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace HTCMage\ProductAttachment\Block\Products\Attachment;

use Magento\Store\Model\StoreManagerInterface;
class UpdateAttachment extends \Magento\Backend\Block\Template
{

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    protected $model;

    private $storeManager;

    protected $_registry;

    protected $helper;
    
    protected $modelEnv;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \HTCMage\ProductAttachment\Model\AttachmentFactory $model,
        \HTCMage\ProductAttachment\Model\ProductAttachmentFactory $modelEnv,
        StoreManagerInterface $storeManager,
        \HTCMage\ProductAttachment\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->_registry = $registry;
        $this->setTabTitle();
        $this->model = $model;
        $this->modelEnv = $modelEnv;
    }

    /**
     * @return mixed
     */
    public function urlUpdate()
    {
        return $this->getUrl('attachment/attachment/resultajax');
    }


    public function getProductId(){
        return $this->_registry->registry('current_product')->getID();
    }
}
