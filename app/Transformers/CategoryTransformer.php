<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'title' => (string)$category->name,
            'details' => (string)$category->description,
            'creationDate' => (string)$category->created_at,
            'lastChange' => (string)$category->updated_at,
            'deletedDate' => isset($category->deleted_at) ? (string) $category->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel' => 'category.buyers', // Goto List of Buyers for category
                    'href' => route('categories.buyers.index', $category->id),
                ],
                [
                    'rel' => 'category.products', // Goto List of Products for category
                    'href' => route('categories.products.index', $category->id),
                ],
                [
                    'rel' => 'category.sellers',  // Goto List of Sellers for category
                    'href' => route('categories.sellers.index', $category->id),
                ],
                [
                    'rel' => 'category.transactions',  // Goto List of Transactionsr for category
                    'href' => route('categories.transactions.index', $category->id),
                ],
            ]
        ];
    }

    /**
     * Map&Translate attributes index by the original attributes
     * Ex in : app\Traits\ApiResponser::sortData() 
     */
    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}