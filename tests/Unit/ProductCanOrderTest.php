<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductCanOrderTest extends TestCase
{
    #[DataProvider('canOrderScenarios')]
    public function test_can_order(int $stock, int $quantity, bool $expected): void
    {
        $product = new Product(['stock' => $stock]);

        $this->assertSame($expected, $product->canOrder($quantity));
    }

    /**
     * @return array<string, array{int, int, bool}>
     */
    public static function canOrderScenarios(): array
    {
        return [
            'zero stock' => [0, 1, false],
            'quantity exceeds stock' => [5, 6, false],
            'quantity equals stock' => [5, 5, true],
            'zero quantity' => [5, 0, false],
        ];
    }
}
