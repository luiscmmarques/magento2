<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Quote\Model\Cart;

use Magento\Quote\Api\CartTotalManagementInterface;

/**
 * @inheritDoc
 */
class CartTotalManagement implements CartTotalManagementInterface
{
    /**
     * @var \Magento\Quote\Api\ShippingMethodManagementInterface
     */
    protected $shippingMehodManagement;

    /**
     * @var \Magento\Quote\Api\PaymentMethodManagementInterface
     */
    protected $paymentMethodManagement;

    /**
     * @var \Magento\Quote\Api\CartTotalRepositoryInterface
     */
    protected $cartTotalsRepository;

    /**
     * @var \Magento\Quote\Model\Cart\TotalsAdditionalDataProcessor
     */
    protected $dataProcessor;

    /**
     * @param \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManagement
     * @param \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement
     * @param \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalsRepository
     * @param \Magento\Quote\Model\Cart\TotalsAdditionalDataProcessor $dataProcessor
     */
    public function __construct(
        \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManagement,
        \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalsRepository,
        \Magento\Quote\Model\Cart\TotalsAdditionalDataProcessor $dataProcessor
    ) {
        $this->shippingMehodManagement = $shippingMethodManagement;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->cartTotalsRepository = $cartTotalsRepository;
        $this->dataProcessor = $dataProcessor;
    }

    /**
     * {@inheritDoc}
     */
    public function collectTotals(
        $cartId,
        $shippingCarrierCode = null,
        $shippingMethodCode = null,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\TotalsAdditionalDataInterface $additionalData = null
    ) {
        if ($shippingCarrierCode && $shippingMethodCode) {
            $this->shippingMehodManagement->set($cartId, $shippingCarrierCode, $shippingMethodCode);
        }
        $this->paymentMethodManagement->set($cartId, $paymentMethod);
        if ($additionalData !== null) {
            $this->dataProcessor->process($additionalData, $cartId);
        }
        return $this->cartTotalsRepository->get($cartId);
    }
}
