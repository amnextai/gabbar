<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Payments;

use Billing\Domain\Entities\PlanEntity;

/**
 * This interface is used to represent an offline payment gateway such as
 * bank transfer or manual payment.
 */
interface OfflinePaymentGatewayInterface extends PaymentGatewayInterface
{
    /**
     * Returns the icon of the payment gateway.
     *
     * @return string|null The icon of the payment gateway.
     */
    public function getIcon(): ?string;

    /**
     * Returns whether the payment gateway can be used for the given plan.
     *
     * @param PlanEntity $plan The plan to check.
     *
     * @return bool Whether the payment gateway can be used for the given plan.
     */
    public function supportsPlan(PlanEntity $plan): bool;
}
