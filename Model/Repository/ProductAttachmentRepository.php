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
use HTCMage\ProductAttachment\Model\ResourceModel\ProductAttachmentFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Class ProductAttachmentRepository
 * @package HTCMage\ProductAttachment\Model\Repository
 */
class ProductAttachmentRepository
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var \HTCMage\ProductAttachment\Model\ProductAttachmentFactory
     */
    private $productAttachmentFactory;
    /**
     * @var ResourceModel\Attachment|ProductAttachmentFactory
     */
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
        ProductAttachmentFactory $resource,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productAttachmentFactory = $productAttachmentFactory;
        $this->resource = $resource;
        $this->productRepository = $productRepository;
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

    /**
     * @param $attachmentId
     * @param $productId
     */
    public function deleteProduct($attachmentId, $productId)
    {
        $modelEnv = $this->create();
        $data = $this->getProductByFilterAttachment($attachmentId);

        foreach ($data as $value) {
            if ($value['id_product'] == $productId) {
                $modelEnv->load($value['id']);
                $modelEnv->delete();
            }
        }
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
     * @param $idAttachment
     * @return mixed
     */
    public function getProductByFilterAttachment($idAttachment)
    {
        $collection = $this->create()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        return $collection->getData();
    }

    /**
     * @param $attachmentId
     */
    public function deleteProductsIfEdit($attachmentId)
    {
        $modelEnv = $this->create();
        $data = $this->getProductByFilterAttachment($attachmentId);

        foreach ($data as $value) {
            // Delete attribute
            $product = $this->productRepository->getById($value['id_product']);
            $attachment = $product->getData('attachment');
            $listAttachment = explode(',', $attachment);
            if (in_array($attachmentId, $listAttachment)) {
                $key = array_search($attachmentId, $listAttachment);
                unset($listAttachment[$key]);
                $listAttachment = implode(',', $listAttachment);
                $product->setData('attachment', $listAttachment);
                $this->productRepository->save($product);
            }

            // Delete Product
            $modelEnv->load($value['id']);
            $modelEnv->delete();
        }
    }

    /**
     * @param $attachmentId
     * @param $productId
     */
    public function addProductAttachment($attachmentId, $productId)
    {
        $modelEnv = $this->create();
        $dataEnv['id_product'] = $productId;
        $dataEnv['id_attachment'] = $attachmentId;
        $modelEnv->setData($dataEnv);
        $modelEnv->save();
    }

    /**
     * @param $attachmentProduct
     * @param $idAttachment
     */
    public function addProductsAttachment($attachmentProduct, $idAttachment)
    {

        $modelEnv = $this->create();
        if (!empty($attachmentProduct)) {
            foreach ($attachmentProduct as $productId => $value) {
                $dataEnv['id_product'] = $productId;
                $dataEnv['id_attachment'] = $idAttachment;
                $modelEnv->setData($dataEnv);
                $modelEnv->save();
            }
        }
    }

    /**
     * @param $productId
     * @param $attachmentId
     */
    public function getAttachmentOfProduct($productId, $attachmentId)
    {
        $product = $this->productRepository->getById($productId);
        $attachment = $product->getData('attachment');
        $listAttachment = explode(',', $attachment);
        if (!in_array($attachmentId, $listAttachment)) {
            array_push($listAttachment, $attachmentId);
            $listAttachment = implode(',', $listAttachment);
            $product->setData('attachment', $listAttachment);
            $this->productRepository->save($product);
        }
    }
}
