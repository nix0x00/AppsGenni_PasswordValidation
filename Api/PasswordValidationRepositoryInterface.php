<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Api;

use AppsGenni\PasswordValidation\Api\Data\PasswordValidationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Password Validation Repository Interface
 * to save the data
 *
 * Interface PasswordValidationRepositoryInterface
 */
interface PasswordValidationRepositoryInterface
{
    /**
     * Method to save the persistent data
     *
     * @param PasswordValidationInterface $passwordValidation
     * @return PasswordValidationInterface
     */
    public function save(PasswordValidationInterface $passwordValidation);

    /**
     * Retrieve Password Validation data By ID
     *
     * @param int $entityId
     * @return PasswordValidationInterface
     */
    public function getById(int $entityId) : PasswordValidationInterface;

    /**
     * Retrieve Password Entries List
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Password Entry By Object
     *
     * @param PasswordValidationInterface $digitalCashPoints
     * @return bool
     */
    public function delete(PasswordValidationInterface $digitalCashPoints) : bool;
}
