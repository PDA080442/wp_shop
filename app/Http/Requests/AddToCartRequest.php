<?php

namespace App\Http\Requests;

use App\Contracts\ProductServiceInterface;
use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    private ?Product $resolvedProduct = null;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(ProductServiceInterface $productService): array
    {
        return [
            function (Validator $validator) use ($productService): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->resolvedProduct = $productService->getById((int) $this->input('product_id'));
                $quantity = (int) $this->input('quantity');

                if (! $this->resolvedProduct->isInStock()) {
                    $validator->errors()->add('quantity', __('cart.out_of_stock'));

                    return;
                }

                $existing = (int) (session('cart', [])[$this->resolvedProduct->id] ?? 0);
                $totalQuantity = $existing + $quantity;

                if (! $this->resolvedProduct->canOrder($totalQuantity)) {
                    $validator->errors()->add('quantity', __('cart.insufficient_stock', [
                        'stock' => $this->resolvedProduct->stock,
                    ]));
                }
            },
        ];
    }

    public function product(): Product
    {
        return $this->resolvedProduct ??= resolve(ProductServiceInterface::class)
            ->getById((int) $this->validated('product_id'));
    }
}
