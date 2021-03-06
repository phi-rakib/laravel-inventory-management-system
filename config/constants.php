<?php

return [

    'roles' => [
        'admin' => 1,
        'salesperson' => 2,
        'supplier' => 3,
        'customer' => 4,
    ],

    'orderStatus' => [
        'new' => 1,
        'checkout' => 2,
        'paid' => 3,
        'failed' => 4,
        'shipped' => 5,
        'delivered' => 6,
        'returned' => 7,
        'complete' => 8,
    ],

    'transaction' => [
        'type' => [
            'credit' => 1,
            'debit' => 2,
        ],

        'mode' => [
            'cashOnDelivery' => 1,
            'cheque' => 2,
            'draft' => 3,
            'wired' => 4,
            'online' => 5,
        ],

        'status' => [
            'new' => 1,
            'cancelled' => 2,
            'failed' => 3,
            'pending' => 4,
            'declined' => 5,
            'rejected' => 6,
            'success' => 7,
        ],
    ],

    'pagination' => [
        'max_item' => 10,
    ],

    'test' => [
        'user' => [
            'max_item' => 15,
        ],
        'category' => [
            'total_parent_item' => 5,
        ],
        'brand' => [
            'max_item' => 15,
        ],
        'product' => [
            'max_item' => 100,
        ],
    ],

];
