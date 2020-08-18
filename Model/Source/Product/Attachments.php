<?php

namespace HTCMage\ProductAttachment\Model\Source\Product;

use HTCMage\ProductAttachment\Model\Repository\AttachmentRepository;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\App\RequestInterface;

/**
 * Class Attachments
 * @package HTCMage\ProductAttachment\Model\Source\Product
 */
class Attachments extends AbstractSource
{
    /**
     * @var
     */
    protected $_options;

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepository;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Attachments constructor.
     * @param AttachmentRepository $attachmentRepository
     * @param RequestInterface $request
     */
    public function __construct(
        AttachmentRepository $attachmentRepository,
        RequestInterface $request
    )
    {
        $this->attachmentRepository = $attachmentRepository;
        $this->request = $request;
    }

    /**
     * @return array|null
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $storeId = (int)$this->request->getParam('store');
            $attachments = $this->attachmentRepository->getAttachmentByStore($storeId);
            $attachmentArr = [];
            foreach ($attachments as $attachment) {
                $attachmentArr[] = ['value' => (int)$attachment['id'], 'label' => __($attachment['title'])];
            }

            $this->_options = $attachmentArr;
        }

        return $this->_options;
    }

}