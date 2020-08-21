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
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AttachmentDowload
 * @package HTCMage\ProductAttachment\Controller\Attachment
 */
class AttachmentDowload extends Action
{

    /**
     * @var
     */
    protected $attachmentRepository;
    /**
     * @var helper
     */
    protected $helper;
    /**
     * @var JsonHelper
     */
    protected $resultJsonFactory;
    /**
     * @var resultPageFactory
     */
    protected $resultPageFactory;


    /**
     * AttachmentDowload constructor.
     * @param Context $context
     * @param HTCMage\ProductAttachment\Model\Repository\AttachmentRepository $attachmentRepository
     * @param resultJsonFactory $resultJsonFactory
     * @param ScopeConfigInterface $scopeConfig
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
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;

    }

    /**
     * Execute
     *
     * @return ResultInterface
     */
    public function execute()
    {
        if ($this->helper->isEnable() == 1) {
            $result = $this->resultJsonFactory->create();
            $notification = 'true';
            $url = "";
            $name = "";
            $file = "";
            $attachment = $this->getAttachmentDownload();
            if (count($attachment)) {
                if ($attachment['limited'] == 1) {
                    if ($attachment['number_of_download'] > 0) {
                        try {
                            $attachment['number_of_download'] = $attachment['number_of_download'] - 1;
                            $attachmentOld = $this->attachmentRepository->getById($attachment['id']);
                            $attachmentNew = $attachmentOld->setData($attachment);
                            $this->attachmentRepository->save($attachmentNew);
                            $url = $this->getUrlDownloadAttachment($attachment);
                            $file = $attachment['file'];
                        } catch (Exception $e) {
                            $notification = __('$e');
                        }
                    } else {
                        $notification = __('Download expired');
                    }
                } else {
                    $url = $this->getUrlDownloadAttachment($attachment);
                }
            } else {
                $notification = __('There are no attachments');
            }
            $data['url'] = $url;
            $data['notification'] = $notification;
            $data['file'] = $file;
            return $result->setData($data);
        }
    }

    /**
     * @return mixed
     */
    public function getAttachmentDownload()
    {
        $store = $this->helper->getStore();
        $customerGroup = $this->helper->getCustomerGroup();
        $idAttachment = $this->getRequest()->getParam('idAttachment');
        $attachment = $this->attachmentRepository->getAttachmentDownload($store, $customerGroup, $idAttachment);
        return $attachment;
    }

    /**
     * @param $attachment
     * @return string
     */
    public function getUrlDownloadAttachment($attachment)
    {
        if ($attachment['type'] == 0) {
            return $this->helper->getPathFile($attachment['file']);
        } else {
            return $attachment['url'];
        }
    }
}
