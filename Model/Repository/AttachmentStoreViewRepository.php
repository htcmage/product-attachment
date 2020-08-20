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

namespace HTCMage\ProductAttachment\Model\Repository;

use Exception;
use HTCMage\ProductAttachment\Model\AttachmentStoreViewFactory;
use HTCMage\ProductAttachment\Model\ResourceModel\AttachmentStoreView;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Class AttachmentStoreViewRepository
 * @package HTCMage\ProductAttachment\Model\Repository
 */
class AttachmentStoreViewRepository
{

    /**
     * @var AttachmentStoreViewFactory
     */
    private $attachmentStoreFactory;

    /**
     * @var ResourceModel\Attachment|AttachmentStoreView
     */
    private $resource;

    /**
     * AttachmentStoreViewRepository constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Session $customerSession
     * @param AttachmentFactory $AttachmentFactory
     * @param ResourceModel\Attachment $resource
     */
    public function __construct(
        AttachmentStoreViewFactory $attachmentStoreFactory,
        AttachmentStoreView $resource
    )
    {
        $this->attachmentStoreFactory = $attachmentStoreFactory;
        $this->resource = $resource;
    }

    /**
     * Save process
     *
     * @param Attachment $modelStoreViewAttachment
     * @return Attachment|null
     */
    public function save(StoreViewAttachment $modelStoreViewAttachment)
    {
        try {
            $this->resource->save($modelStoreViewAttachment);
            return $modelStoreViewAttachment;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Delete
     *
     * @param Attachment $modelStoreViewAttachment
     * @return bool
     */
    public function delete(StoreViewAttachment $modelStoreViewAttachment)
    {
        try {
            $this->resource->delete($modelStoreViewAttachment);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $listStore
     * @param $attachmentId
     * @return bool
     */
    public function saveStoreLinks($listStore, $attachmentId)
    {
        $model = $this->getById();
        $this->deleteStoreLink($attachmentId);
        if (!empty($listStore)) {
            foreach ($listStore as $storeId) {
                $dataStore['store_id'] = $storeId;
                $dataStore['id_attachment'] = $attachmentId;
                $model->setData($dataStore);
                $model->save();
            }
        } else {
            return false;
        }
    }

    /**
     * Get Attachment by id
     *
     * @param int|null $id
     * @return Attachment
     */
    public function getById($id = null)
    {
        $model = $this->attachmentStoreFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    /**
     * @param $idAttachment
     */
    public function deleteStoreLink($idAttachment)
    {
        $model = $this->getById();
        $data = $this->getStoreLinkByAttachment($idAttachment);
        foreach ($data as $value) {
            $model->load($value['id']);
            $model->delete();
        }
    }

    /**
     * @param $idAttachment
     * @return mixed
     */
    public function getStoreLinkByAttachment($idAttachment)
    {
        $collection = $this->getById()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        return $collection->getData();
    }
}
