<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Model;

use AppsGenni\PasswordValidation\Api\Data\PasswordValidationInterface;
use AppsGenni\PasswordValidation\Api\Data\PasswordValidationSearchResultInterface;
use AppsGenni\PasswordValidation\Api\Data\PasswordValidationSearchResultInterfaceFactory;
use AppsGenni\PasswordValidation\Api\PasswordValidationRepositoryInterface;
use AppsGenni\PasswordValidation\Model\ResourceModel\PasswordValidation as ResourceModel;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use AppsGenni\PasswordValidation\Model\ResourceModel\Collection\CollectionFactory;
use AppsGenni\PasswordValidation\Model\ResourceModel\Collection\Collection;
use Exception;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Model\AbstractModel;

/**
 * Repository for Password Validation which implements
 * save method in order to keep the persistent data
 * in record
 *
 * Class PasswordValidationRepository
 */
class PasswordValidationRepository implements PasswordValidationRepositoryInterface
{
    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var PasswordValidationFactory
     */
    private $passwordValidationFactory;

    /**
     * @var ResourceModel
     */
    private $resourceModel;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var PasswordValidationSearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * PasswordValidationRepository constructor.
     *
     * @param ResourceModel $resourceModel
     * @param PasswordValidationFactory $passwordValidationFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param CollectionFactory $collectionFactory
     * @param PasswordValidationSearchResultInterfaceFactory $searchResultFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        PasswordValidationFactory $passwordValidationFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        CollectionFactory $collectionFactory,
        PasswordValidationSearchResultInterfaceFactory $searchResultFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->passwordValidationFactory = $passwordValidationFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Method to save the persistent data
     *
     * @param PasswordValidationInterface $passwordValidation
     * @return PasswordValidationInterface
     * @throws CouldNotSaveException
     */
    public function save(PasswordValidationInterface $passwordValidation): PasswordValidationInterface
    {
        try {
            /** @var PasswordValidationInterface|AbstractModel $passwordValidation */
            $this->resourceModel->save($passwordValidation);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__('Unable to save data: %1', $e->getMessage()));
        }
        return $passwordValidation;
    }

    /**
     * Retrieve Password Validation data By ID
     *
     * @param int $entityId
     * @return PasswordValidationInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId) : PasswordValidationInterface
    {
        /** @var PasswordValidationInterface|AbstractModel $points */
        $model = $this->passwordValidationFactory->create();
        $this->resourceModel->load($model, $entityId);
        if (!$points->getId()) {
            throw new NoSuchEntityException(__('Requested Data dopes not exist.'));
        }
        return $model;
    }

    /**
     * Retrieve Password Entries List
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $collection = $this->collectionFactory->create();
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * Delete Password Entry By Object
     *
     * @param PasswordValidationInterface $validation
     * @return bool
     * @throws StateException
     */
    public function delete(PasswordValidationInterface $validation) : bool
    {
        $id = $validation->getEntityId();
        try {
            unset($this->instances[$id]);
            /** @var PasswordValidationInterface|AbstractModel $validation */
            $this->resourceModel->delete($validation);
        } catch (Exception $e) {
            throw new StateException(__('Unable to remove data %1', $id));
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Add Filters to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    public function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $condition = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $condition[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $condition);
        }
    }

    /**
     * Add Sort Orders to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    public function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * Add Paging to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    public function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * Build Search Result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return PasswordValidationSearchResultInterface
     */
    public function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }
}
