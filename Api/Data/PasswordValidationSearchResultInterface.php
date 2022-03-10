<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PasswordValidationSearchResultInterface
 */
interface PasswordValidationSearchResultInterface extends SearchResultsInterface
{
    /**
     * Set Items
     *
     * @param PasswordValidationInterface[] $items
     * @return void
     */
    public function setItems(array $items);

    /**
     * Get Items
     *
     * @return PasswordValidationInterface[]
     */
    public function getItems();
}
