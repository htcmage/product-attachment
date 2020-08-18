<?php

namespace HTCMage\ProductAttachment\Controller\Adminhtml\Attachment;

use Exception;
use HTCMage\ProductAttachment\Model\ResourceModel\Attachment\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassEnable
 */
class MassEnable extends Action
{
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * Const
     */
    const ATTACHMENT_ENABLE = 1;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $attachment) {
            $attachment->setData('status',$this::ATTACHMENT_ENABLE);
            $attachment->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been enable.', $collectionSize));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('attachment/attachment/index');
    }
}
