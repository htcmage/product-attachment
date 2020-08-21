<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Controller\Attachment;

use HTCMage\ProductAttachment\Helper\Data;
use HTCMage\ProductAttachment\Model\Repository\AttachmentRepository;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class ResultAjax
 * @package HTCMage\ProductAttachment\Controller\Attachment
 */
class ResultAjax extends Action
{
    /**
     * Const
     */
    const DISPLAY_DETAIL = 0;
    /**
     * Const
     */
    const DISPLAY_FOOTER = 1;
    /**
     * Const
     */
    const DISPLAY_AFTER_CART = 2;
    /**
     * @var
     */
    protected $attachmentRepository;
    /**
     * @var JsonHelper
     */
    protected $resultJsonFactory;
    /**
     * @var resultPageFactory
     */
    protected $resultPageFactory;
    /**
     * @var helper
     */
    protected $helper;


    /**
     * ResultAjax constructor.
     * @param Context $context
     * @param HTCMage\ProductAttachment\Model\Repository\AttachmentRepository $attachmentRepository
     * @param resultJsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        AttachmentRepository $attachmentRepository,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Data $helper
    )
    {
        parent::__construct($context);
        $this->attachmentRepository = $attachmentRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;

    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if ($this->helper->isEnable() == 1) {
            $result = $this->resultJsonFactory->create();
            $data['detail'] = trim($this->getHtml($this->listAttachment($this::DISPLAY_DETAIL)));
            $data['footer'] = trim($this->getHtml($this->listAttachment($this::DISPLAY_FOOTER)));
            $data['aftercart'] = trim($this->getHtml($this->listAttachment($this::DISPLAY_AFTER_CART)));
            return $result->setData($data);
        }
    }

    /**
     * @param $listProductAttachment
     * @return mixed
     */
    public function getHtml($listProductAttachment)
    {
        $resultPage = $this->resultPageFactory->create();
        $html = $resultPage->getLayout()
            ->createBlock('HTCMage\ProductAttachment\Block\Products\Attachment\Attachment')->setData('listProductAttachment', $listProductAttachment)
            ->setTemplate('HTCMage_ProductAttachment::attachmentlist.phtml')
            ->toHtml();
        return $html;
    }

    /**
     * @return array
     */
    public function listAttachment($display)
    {
        $store = $this->helper->getStore();
        $customerGroup = $this->helper->getCustomerGroup();
        $productId = $this->getRequest()->getParam('product');
        $listAttachment = $this->attachmentRepository->getAttachment($display, $store, $customerGroup, $productId);
        $data = [];
        if ($listAttachment) {
            foreach ($listAttachment as $attachment) {
                if ($attachment['limited'] == 1) {
                    if ($attachment['number_of_download'] > 0) {
                        array_push($data, $attachment);
                    }
                } else {
                    array_push($data, $attachment);
                }
            }
        }
        return $data;
    }
}
