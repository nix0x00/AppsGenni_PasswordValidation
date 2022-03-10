<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Model;

use AppsGenni\PasswordValidation\Api\Data\PasswordValidationInterface;
use Magento\Framework\Model\AbstractModel;
use AppsGenni\PasswordValidation\Model\ResourceModel\PasswordValidation as ResourceModel;

/**
 * Model class for LV Password Validation
 *
 * Class PasswordValidation
 */
class PasswordValidation extends AbstractModel implements PasswordValidationInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = self::MAIN_TABLE;

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Set Customer ID
     *
     * @param int $id
     * @return $this
     */
    public function setCustomerId(int $id): self
    {
        $this->setData(self::CUSTOMER_ID, $id);
        return $this;
    }

    /**
     * Retrieve Customer ID
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set Customer Email
     *
     * @param string $email
     * @return $this
     */
    public function setCustomerEmail(string $email): self
    {
        $this->setData(self::CUSTOMER_EMAIL, $email);
        return $this;
    }

    /**
     * Retrieve Customer Email
     *
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Set Password Hash
     *
     * @param string $hash
     * @return $this
     */
    public function setPasswordHash(string $hash): self
    {
        $this->setData(self::PASSWORD_HASH, $hash);
        return $this;
    }

    /**
     * Retrieve Password Hash
     *
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->getData(self::PASSWORD_HASH);
    }
}
