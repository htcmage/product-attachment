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

namespace HTCMage\ProductAttachment\Block\Adminhtml\Products;

use HTCMage\ProductAttachment\Block\Adminhtml\Edit\Tab\Product;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Class AssignProducts
 * @package HTCMage\ProductAttachment\Block\Adminhtml\Products
 */
class AssignProducts extends Template
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
     * @var Registry
     */
    protected $registry;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EncoderInterface $jsonEncoder,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
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
     * Retrieve instance of grid block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                Product::class,
                'attachment.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * @return string
     */
    public function getProductsJson()
    {
        $productAttachment = $this->getProductAttachment();
        $result = '{';
        if ($productAttachment) {
            foreach ($productAttachment as $value) {
                $result .= '"' . $value . '"' . ':' . '"' . '2' . '",';
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

    /**
     * @param $string
     * @return false|string[]
     */
    public function arayProduct($string)
    {
        return explode(',', $string);
    }
}
