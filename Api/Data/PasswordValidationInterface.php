<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Api\Data;

/**
 * Interface for Password Validation Attributes
 *
 * Interface PasswordValidationInterface
 */
interface PasswordValidationInterface
{
    const MAIN_TABLE = 'lv_password_validation';
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const PASSWORD_HASH = 'password_hash';

    /**
     * Set Entity ID
     *
     * @param mixed $id
     * @return mixed
     */
    public function setEntityId($id);

    /**
     * Retrieve Entity ID
     *
     * @return mixed
     */
    public function getEntityId();

    /**
     * Set Customer ID
     *
     * @param int $id
     * @return $this
     */
    public function setCustomerId(int $id): PasswordValidationInterface;

    /**
     * Retrieve Customer ID
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Set Customer Email
     *
     * @param string $email
     * @return $this
     */
    public function setCustomerEmail(string $email): PasswordValidationInterface;

    /**
     * Retrieve Customer Email
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Set Password Hash
     *
     * @param string $hash
     * @return $this
     */
    public function setPasswordHash(string $hash): PasswordValidationInterface;

    /**
     * Retrieve Password Hash
     *
     * @return string
     */
    public function getPasswordHash(): string;
}
