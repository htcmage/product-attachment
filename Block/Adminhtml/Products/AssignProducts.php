<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace HTCMage\ProductAttachment\Block\Adminhtml\Products;

class AssignProducts extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'HTCMage_ProductAttachment::attachment/assign_products.phtml';

    /**
     * @var \HTCMage\ProductAttachment\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                \HTCMage\ProductAttachment\Block\Adminhtml\Edit\Tab\Product::class,
                'attachment.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getProductsJson()
    {
        $productAttachment = $this->getProductAttachment();
        $result = '{';
        if ($productAttachment) {
            foreach ($productAttachment as  $value) {
                $result.='"'.$value.'"'.':'.'"'.'2'.'",';
            }
        }
        $result = rtrim($result, ",");
        $result .= '}';
        return $result;
    }

    /**
     * Retrieve current category instance
     *
     * @return array|null
     */
    public function getProductAttachment()
    {
        return $this->registry->registry('product_attachment');
    }
    public function arayProduct($string){
        return explode(',', $string);
    }
}
