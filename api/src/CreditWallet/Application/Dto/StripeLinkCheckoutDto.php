<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Dto;

class StripeLinkCheckoutDto
{
    public function __construct(
        public string $productId,
        public int $credit,
        public string $urlCallBack,
    ) {
    }
}
