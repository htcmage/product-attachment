<?php

namespace HTCMage\ProductAttachment\Model\Attachment;

use Magento\Framework\App\Request\DataPersistorInterface;
use HTCMage\ProductAttachment\Model\ResourceModel\Attachment\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    private $loadedData;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Prince\Faq\Model\ResourceModel\Faq\CollectionFactory
     */
    
    public $collection;

    protected $attachmentStoreViewRepository;

    protected $attachmentCustomerGroupRepository;

    protected $attachmentDisplayRepository;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentStoreViewRepository $attachmentStoreViewRepository,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentCustomerGroupRepository $attachmentCustomerGroupRepository,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentDisplayRepository $attachmentDisplayRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        $this->attachmentStoreViewRepository = $attachmentStoreViewRepository;
        $this->attachmentCustomerGroupRepository = $attachmentCustomerGroupRepository;
        $this->attachmentDisplayRepository = $attachmentDisplayRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            $fullData = $this->loadedData;
            if ( $model->getIcon() ) {
                $m['icon'][0]['name'] = $model->getIcon();
                $m['icon'][0]['url'] = $this->getMediaUrl().$model->getIcon();
            }

            if ($model->getFile()) {
                $m['file'][0]['name'] = $model->getFile();
                $m['file'][0]['url'] = $this->getMediaUrlFile().$model->getFile();
            }
            $idAttachment = $model->getId();
            $collection = $this->attachmentStoreViewRepository->getById()->getCollection();
            $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
            $storeLink = $collection->getData();
            $listStore = [];
            foreach ( $storeLink as $data ) {
                $listStore[] = $data['store_id'];
            }
            $storeId = implode( ',', $listStore);
            $m['store_id'] = $storeId;

            $collection = $this->attachmentCustomerGroupRepository->getById()->getCollection();
            $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
            $customerGroup = $collection->getData();
            $group = [];
            foreach ( $customerGroup as $data ) {
                $group[] = $data['group_id'];
            }
            $customerGroup = implode( ',', $group);
            $m['customer_group'] = $group;

            $collection = $this->attachmentDisplayRepository->getById()->getCollection();
            $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
            $displayAttachment = $collection->getData();
            $display = [];
            foreach ( $displayAttachment as $data ) {
                $display[] = $data['display'];
            }
            $displayAttachment = implode( ',', $display);
            $m['display'] = $display;

            $this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
        }
        $data = $this->dataPersistor->get('attachment');


        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('attachment');
        }

        return $this->loadedData;
    }
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'htcmage/attachment/';
        return $mediaUrl;
    }
     public function getMediaUrlFile()
    {
        $mediaUrlFile = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'htcmage/attachment/file/';
        return $mediaUrlFile;
    }
}
