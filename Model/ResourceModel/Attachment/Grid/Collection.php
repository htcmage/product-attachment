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

namespace HTCMage\ProductAttachment\Model\ResourceModel\Attachment\Grid;

use HTCMage\ProductAttachment\Model\ResourceModel\Attachment;
use HTCMage\ProductAttachment\Model\ResourceModel\Attachment\Collection as CommentCollection;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Psr\Log\LoggerInterface;

/**
 * Class Collection
 * @package HTCMage\ProductAttachment\Model\ResourceModel\Attachment\Grid
 */
class Collection extends CommentCollection implements SearchResultInterface
{
    /**
     * Aggregations
     * @var \Magento\Framework\Search\AggregationInterface
     */
    private $aggregations;

    /**
     * Collection constructor.
     *
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param string $model
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        $model = Document::class,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    )
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

        $this->_init(
            $model,
            Attachment::class
        );
    }

    /**
     * Get Aggregations
     *
     * @return \Magento\Framework\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Set Aggregations
     *
     * @param AggregationInterface $aggregations
     * @return void
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * Get search criteria.
     *
     * @return SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return $this
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param array $items
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return Collection
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * Get Item().
     *
     * @return DocumentInterface[]|DataObject[].
     */
    public function getItems()
    {
        $items = parent::getItems();
        return $items;
    }
}


