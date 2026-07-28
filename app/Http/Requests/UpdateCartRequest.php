<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
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
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                /** @var Product $product */
                $product = $this->route('product');
                $quantity = (int) $this->input('quantity');

                if (! $product->isInStock()) {
                    $validator->errors()->add('quantity', __('cart.out_of_stock'));

                    return;
                }

                if (! $product->canOrder($quantity)) {
                    $validator->errors()->add('quantity', __('cart.insufficient_stock', [
                        'stock' => $product->stock,
                    ]));
                }
            },
        ];
    }
}
