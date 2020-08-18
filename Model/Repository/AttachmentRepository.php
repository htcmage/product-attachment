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
use HTCMage\ProductAttachment\Model\AttachmentFactory;
use HTCMage\ProductAttachment\Model\ProductAttachmentFactory;
use HTCMage\ProductAttachment\Model\ResourceModel\Attachment;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Class AttachmentRepository
 * @package HTCMage\ProductAttachment\Model\Repository
 */
class AttachmentRepository
{

    /**
     * Const
     */
    const STORE_VIEW_ATTACHMENT = 'htcmage_productattachment_store_view';

    /**
     * Const
     */
    const CUSTOMER_GROUP_ATTACHMENT = 'htcmage_productattachment_customer_group';

    /**
     * Const
     */
    const PRODUCT_ATTACHMENT = 'htcmage_productattachment_product';

    /**
     * Const
     */
    const DISPLAY_ATTACHMENT = 'htcmage_productattachment_display';

    /**
     *
     */
    const ENABLE = '1';
    /**
     *
     */
    const DISABLE = '0';

    /**
     * Name of Main Table.
     *
     * @var string
     */
    protected $mainTable = 'htcmage_productattachment';

    /**
     * @var AttachmentFactory
     */
    private $attachmentFactory;

    /**
     * @var ResourceModel\Attachment|Attachment
     */
    private $resource;

    /**
     * @var ProductAttachmentFactory
     */
    private $resourceModelPA;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * HTTP context
     *
     * @var \Magento\Framework\App\Http\Context $httpContext
     */
    private $httpContext;

    /**
     * Store manager
     *
     * @var StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * AttachmentRepository constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Session $customerSession
     * @param AttachmentFactory $AttachmentFactory
     * @param ResourceModel\Attachment $resource
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\Http\Context $httpContext,
        Session $customerSession,
        AttachmentFactory $attachmentFactory,
        Attachment $resource,
        ProductAttachmentFactory $resourceModelPA
    )
    {
        $this->storeManager = $storeManager;
        $this->httpContext = $httpContext;
        $this->customerSession = $customerSession;
        $this->attachmentFactory = $attachmentFactory;
        $this->resource = $resource;
        $this->resourceModelPA = $resourceModelPA;
    }

    /**
     * @return mixed
     */
    public function getMainTable()
    {
        return $this->resource->getTable($this->mainTable);
    }

    /**
     * @return ProductAttachmentFactory
     */
    public function getResourceModelPA()
    {
        return $this->resourceModelPA;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function create($id = null)
    {
        $model = $this->attachmentFactory->create();
        return $model;
    }

    /**
     * Save process
     *
     * @param Attachment $modelAttachment
     * @return Attachment|null
     */
    public function save($modelAttachment)
    {
        try {
            $this->resource->save($modelAttachment);
            return $modelAttachment;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Delete
     *
     * @param Attachment $modelAttachment
     * @return bool
     */
    public function delete($modelAttachment)
    {
        try {
            $this->resource->delete($modelAttachment);
        } catch (Exception $exception) {
            return false;
        }

    }

    /**
     * Get Banner by Id
     *
     * @param $id
     * @return Banner
     */
    public function getById($id = null)
    {
        $model = $this->attachmentFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    /**
     * @param $display
     * @param $store
     * @param $customerGroup
     * @param $productId
     * @return mixed
     */
    public function getAttachment($display, $store, $customerGroup, $productId)
    {
        $stores = [0, $store];
        $adapter = $this->resource->getConnection();
        $storeTbl = $this->resource->getTable(self::STORE_VIEW_ATTACHMENT);
        $groupTbl = $this->resource->getTable(self::CUSTOMER_GROUP_ATTACHMENT);
        $productTbl = $this->resource->getTable(self::PRODUCT_ATTACHMENT);
        $displayTbl = $this->resource->getTable(self::DISPLAY_ATTACHMENT);
        $select = $adapter->select()->from(
            ['mainTbl' => $this->resource->getMainTable()],
            ['*']
        )->joinLeft(
            ['storeViewTbl' => $storeTbl],
            'mainTbl.id = storeViewTbl.id_attachment',
            []
        )->joinLeft(
            ['groupTbl' => $groupTbl],
            'mainTbl.id = groupTbl.id_attachment',
            []
        )->joinLeft(
            ['productTbl' => $productTbl],
            'mainTbl.id = productTbl.id_attachment',
            []
        )->joinLeft(
            ['displayTbl' => $displayTbl],
            'mainTbl.id = displayTbl.id_attachment',
            []
        )->where(
            'status = ?', self::ENABLE
        )->where(
            'storeViewTbl.store_id IN (?)', $stores
        )->where(
            'groupTbl.group_id = ?', $customerGroup
        )->where(
            'productTbl.id_product = ?', $productId
        )->where(
            'displayTbl.display = ?', $display
        )->order(
            'mainTbl.position ASC'
        );
        return $adapter->query($select);
    }

    /**
     * @param $store
     * @param $productId
     * @return array
     */
    public function getAttachments($store, $productId)
    {
        $stores = [0, $store];
        $adapter = $this->resource->getConnection();
        $storeTbl = $this->resource->getTable(self::STORE_VIEW_ATTACHMENT);
        $productTbl = $this->resource->getTable(self::PRODUCT_ATTACHMENT);
        $select = $adapter->select()->from(
            ['mainTbl' => $this->resource->getMainTable()],
            ['*']
        )->joinLeft(
            ['storeViewTbl' => $storeTbl],
            'mainTbl.id = storeViewTbl.id_attachment',
            []
        )->joinLeft(
            ['productTbl' => $productTbl],
            'mainTbl.id = productTbl.id_attachment',
            []
        )->where(
            'status = ?', self::ENABLE
        )->where(
            'storeViewTbl.store_id IN (?)', $stores
        )->where(
            'productTbl.id_product = ?', $productId
        )->order(
            'mainTbl.position ASC'
        );

        $query = $adapter->query($select);
        $data = [];
        while ($row = $query->fetch()) {
            array_push($data, $row);
        }

        return $data;
    }

    /**
     * @param $store
     * @param $customerGroup
     * @param $idAttachment
     * @return mixed
     */
    public function getAttachmentDownload($store, $customerGroup, $idAttachment)
    {
        $stores = [0, $store];
        $adapter = $this->resource->getConnection();
        $storeTbl = $this->resource->getTable(self::STORE_VIEW_ATTACHMENT);
        $groupTbl = $this->resource->getTable(self::CUSTOMER_GROUP_ATTACHMENT);
        $select = $adapter->select()->from(
            ['mainTbl' => $this->resource->getMainTable()],
            ['*']
        )->joinLeft(
            ['storeViewTbl' => $storeTbl],
            'mainTbl.id = storeViewTbl.id_attachment',
            []
        )->joinLeft(
            ['groupTbl' => $groupTbl],
            'mainTbl.id = groupTbl.id_attachment',
            []
        )->where(
            'mainTbl.id = ?', $idAttachment
        )->where(
            'status = ?', self::ENABLE
        )->where(
            'storeViewTbl.store_id IN (?)', $stores
        )->where(
            'groupTbl.group_id = ?', $customerGroup
        );
        return $adapter->fetchRow($select);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function getAttachmentByStore($store)
    {
        $adapter = $this->resource->getConnection();
        $storeTbl = $this->resource->getTable(self::STORE_VIEW_ATTACHMENT);
        $stores = [0, $store];
        $select = $adapter->select()->from(
            ['mainTbl' => $this->resource->getMainTable()],
            ['*']
        )->joinLeft(
            ['storeViewTbl' => $storeTbl],
            'mainTbl.id = storeViewTbl.id_attachment',
            []
        )->where(
            'storeViewTbl.store_id IN (?)', $stores
        );
        return $adapter->query($select);
    }

    /**
     * @param $productId
     * @return array
     */
    public function getAttachmentByProduct($productId)
    {
        $adapter = $this->resource->getConnection();
        $productTbl = $this->resource->getTable(self::PRODUCT_ATTACHMENT);
        $select = $adapter->select()->from(
            ['mainTbl' => $this->resource->getMainTable()],
            ['*']
        )->joinLeft(
            ['productViewTbl' => $productTbl],
            'mainTbl.id = productViewTbl.id_attachment',
            []
        )->where(
            'productViewTbl.id_product IN (?)', $productId
        );
        $query = $adapter->query($select);
        $data = [];
        while ($row = $query->fetch()) {
            array_push($data, $row);
        }

        return $data;
    }

}
