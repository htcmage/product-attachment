<?php

namespace HTCMage\ProductAttachment\Observer;

use HTCMage\ProductAttachment\Model\Repository\AttachmentRepository;
use HTCMage\ProductAttachment\Model\Repository\ProductAttachmentRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Attachment
 * @package HTCMage\ProductAttachment\Observer
 */
class Attachment implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    public $request;

    /**
     * @var ProductAttachmentRepository
     */
    protected $productAttachmentRepository;

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Attachment constructor.
     * @param RequestInterface $request
     * @param ProductAttachmentRepository $productAttachmentRepository
     * @param AttachmentRepository $attachmentRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterface $request,
        ProductAttachmentRepository $productAttachmentRepository,
        AttachmentRepository $attachmentRepository,
        StoreManagerInterface $storeManager
    )
    {

        $this->request = $request;
        $this->productAttachmentRepository = $productAttachmentRepository;
        $this->attachmentRepository = $attachmentRepository;
        $this->storeManager = $storeManager;
    }


    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getData('product');
        $productId = $product->getId();
        $storeId = $this->storeManager->getStore()->getId();
        $attachments = $this->attachmentRepository->getAttachments($storeId, $productId);
        foreach ($attachments as $attachment) {
            $this->productAttachmentRepository->deleteProduct($attachment['id'], $productId);
        }
        $attachment = $product->getData('attachment');
        $listAttachment = explode(',', $attachment);
        if (!empty($listAttachment)) {
            foreach ($listAttachment as $attachmentId) {
                $this->productAttachmentRepository->addProductAttachment($attachmentId, $productId);
            }
        }
        return $this;
    }
}