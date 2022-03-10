<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Plugin\Account;

use AppsGenni\PasswordValidation\Api\PasswordValidationRepositoryInterface;
use AppsGenni\PasswordValidation\Model\Config\Configuration;
use AppsGenni\PasswordValidation\Model\PasswordValidationFactory;
use AppsGenni\PasswordValidation\Model\ResourceModel\Collection\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use \Exception;

/**
 * Before Plugin to validate new password if it was
 * already used by the customer previously
 *
 * Class ChangePassword
 */
class ChangePassword
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var EncryptorInterface
     */
    private $encryptorInterface;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var PasswordValidationFactory
     */
    private $passwordValidationFactory;

    /**
     * @var PasswordValidationRepositoryInterface
     */
    private $passwordValidationRepository;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * ChangePassword constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param EncryptorInterface $encryptorInterface
     * @param CollectionFactory $collectionFactory
     * @param PasswordValidationFactory $passwordValidationFactory
     * @param PasswordValidationRepositoryInterface $passwordValidationRepository
     * @param AuthenticationInterface $authenticationInterface
     * @param Configuration $configuration
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        EncryptorInterface $encryptorInterface,
        CollectionFactory $collectionFactory,
        PasswordValidationFactory $passwordValidationFactory,
        PasswordValidationRepositoryInterface $passwordValidationRepository,
        AuthenticationInterface $authenticationInterface,
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
        $this->authentication = $authenticationInterface;
        $this->passwordValidationRepository = $passwordValidationRepository;
        $this->passwordValidationFactory = $passwordValidationFactory;
        $this->collectionFactory = $collectionFactory;
        $this->encryptorInterface = $encryptorInterface;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Before Plugin on Change Password to check if is already used
     *
     * @param AccountManagement $accountManagement
     * @param string $email
     * @param string $currentPassword
     * @param string $newPassword
     * @throws InvalidEmailOrPasswordException
     * @throws LocalizedException
     */
    public function beforeChangePassword(
        AccountManagement $accountManagement,
        $email,
        $currentPassword,
        $newPassword
    ) {
        if (!$this->configuration->ifPassValidationEnabled()) {
            return;
        }
        if ($currentPassword == $newPassword) {
            throw new InvalidEmailOrPasswordException(__('Old password cannot be reused. Please change!'));
        }
        try {
            $customer = $this->customerRepository->get($email);
            try {
                $this->authentication->authenticate($customer->getId(), $currentPassword);
            } catch (InvalidEmailOrPasswordException $e) {
                throw new InvalidEmailOrPasswordException(
                    __("The password doesn't match this account. Verify the password and try again.")
                );
            }
            $newHash = $this->encryptorInterface->getHash($newPassword, true);
            if ($this->checkIfCustomerExist($customer->getEmail())) {
                if ($this->validateHash($customer->getEmail(), $newPassword)) {
                    throw new InvalidEmailOrPasswordException(__('Old password cannot be reused. Please change!'));
                } else {
                    $this->saveNewCustomerPassword($customer, $newHash);
                }
            } else {
                $this->saveNewCustomerPassword($customer, $newHash);
            }
        } catch (NoSuchEntityException $e) {
            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }
    }

    /**
     * Validate if the customer is new
     *
     * @param string $email
     * @return bool
     */
    private function checkIfCustomerExist(string $email): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('customer_email', ['eq' => $email]);
        return (count($collection) > 0) ? true : false;
    }

    /**
     * Save the data for the customer
     *
     * @param mixed $customer
     * @param mixed $newHash
     */
    private function saveNewCustomerPassword($customer, $newHash)
    {
        $model = $this->passwordValidationFactory->create();
        $model->setCustomerEmail($customer->getEmail());
        $model->setPasswordHash($newHash);
        $model->setCustomerId((int)$customer->getId());
        $this->passwordValidationRepository->save($model);
    }

    /**
     * Validate Hash
     *
     * @param string $customerEmail
     * @param mixed $newPassword
     * @return bool
     * @throws InvalidEmailOrPasswordException
     */
    private function validateHash(string $customerEmail, $newPassword): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter(
                'customer_email',
                ['eq' => $customerEmail]
            );
        foreach ($collection as $item) {
            $oldHash = $item->getPasswordHash();
            try {
                if ($this->encryptorInterface->validateHash($newPassword, $oldHash)) {
                    return true;
                }
            } catch (Exception $e) {
                throw new InvalidEmailOrPasswordException(__('Old password cannot be reused. Please change!'));
            }
        }
        return false;
    }
}
