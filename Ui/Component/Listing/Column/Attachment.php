<?php

namespace HTCMage\ProductAttachment\Ui\Component\Listing\Column;

use HTCMage\ProductAttachment\Model\Repository\AttachmentRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class Attachment
 * @package HTCMage\ProductAttachment\Ui\Component\Listing\Column
 */
class Attachment implements ArrayInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var AttachmentRepository
     */
    private $attachmentRepository;

    /**
     * Attachment constructor.
     * @param RequestInterface $request
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        RequestInterface $request,
        AttachmentRepository $attachmentRepository
    )
    {
        $this->attachmentRepository = $attachmentRepository;
        $this->request = $request;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $attachmentArr = [];
        $storeId = (int)$this->request->getParam('store');
        $attachments = $this->attachmentRepository->getAttachmentByStore($storeId);
        foreach ($attachments as $attachment) {
            $attachmentArr[] = ['value' => $attachment['id'], 'label' => __($attachment['title'])];
        }
        return $attachmentArr;
    }
}
