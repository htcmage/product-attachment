<?php
/**
 * *
 *  * @author HTCMage Team
 *  * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 *  * @package HTCMage_Attachment
 *
 */

namespace HTCMage\ProductAttachment\Model\Repository;

use Exception;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;


class ProductAttachmentRepository
{

    private $productAttachmentFactory;

    private $resource;

    /**
     * ProductAttachmentRepository constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Session $customerSession
     * @param AttachmentFactory $AttachmentFactory
     * @param ResourceModel\Attachment $resource
     */
    public function __construct(
        \HTCMage\ProductAttachment\Model\ProductAttachmentFactory $productAttachmentFactory,
        \HTCMage\ProductAttachment\Model\ResourceModel\ProductAttachmentFactory $resource
    )
    {
        $this->productAttachmentFactory = $productAttachmentFactory;
        $this->resource = $resource;
    }

    /**
     * Get Attachment by id
     *
     * @param int|null $id
     * @return Attachment
     */
    public function load($id = null)
    {
        $model = $this->productAttachmentFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function create($id = null)
    {
        $model = $this->productAttachmentFactory->create();
        return $model;
    }

    /**
     * Save process
     *
     * @param Attachment $modelAttachment
     * @return Attachment|null
     */
    public function save(ProductAttachment $modelProductAttachment)
    {
        try {
            $this->resource->save($modelProductAttachment);
            return $modelProductAttachment;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Delete
     *
     * @param Attachment $modelAttachment
     * @return bool
     */
    public function delete(ProductAttachment $modelProductAttachment)
    {
        try {
            $this->resource->delete($modelProductAttachment);
        } catch (Exception $exception) {
            return false;
        }
    }


    public function getProductByFilterAttachment($idAttachment)
    {
        $collection = $this->create()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        return $collection->getData();
    }

    public function deleteProductsIfEdit($idCheck){
        $modelEnv = $this->create();
        $data = $this->getProductByFilterAttachment($idCheck);
        foreach ($data as $value) {
            $modelEnv->load($value['id']);
            $modelEnv->delete();
        }

    }

    public function addProductsAttachment($attachmentProduct, $idAttachment){
        $modelEnv = $this->create();
        if( isset($attachmentProduct) && ! empty($attachmentProduct)){
            foreach ($attachmentProduct as $key => $value) {
                $dataEnv['id_product'] = $key;
                $dataEnv['id_attachment'] = $idAttachment;
                $modelEnv->setData($dataEnv);
                $modelEnv->save();
            }
        }
    }
}
