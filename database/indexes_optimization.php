<?php

/**
 */

return [
    'indexes' => [
        'listings' => [
            ['name' => 'idx_status_published', 'columns' => ['status', 'published_at']],
            ['name' => 'idx_city_id', 'columns' => ['city_id']],
            ['name' => 'idx_average_rating', 'columns' => ['average_rating']],
            ['name' => 'idx_views', 'columns' => ['views']],
            ['name' => 'idx_created_at', 'columns' => ['created_at']],
            ['name' => 'idx_status_views', 'columns' => ['status', 'views']],
        ],

        'listing_category' => [
            ['name' => 'idx_category_id', 'columns' => ['category_id']],
            ['name' => 'idx_listing_category', 'columns' => ['listing_id', 'category_id']],
        ],

        'listing_prices' => [
            ['name' => 'idx_listing_price', 'columns' => ['listing_id', 'price']],
            ['name' => 'idx_price', 'columns' => ['price']],
        ],

        'listing_features' => [
            ['name' => 'idx_listing_features', 'columns' => ['listing_id', 'feature_id']],
        ],

        'favorites' => [
            ['name' => 'idx_user_listing', 'columns' => ['user_id', 'listing_id']],
        ],

        'reviews' => [
            ['name' => 'idx_listing_user', 'columns' => ['listing_id', 'user_id']],
        ],

        'users' => [
            ['name' => 'idx_email', 'columns' => ['email']],
        ],
    ],

    /**
     */
    'sql' => <<<SQL
-- Listings Indexes
CREATE INDEX idx_status_published ON listings(status, published_at);
CREATE INDEX idx_city_id ON listings(city_id);
CREATE INDEX idx_average_rating ON listings(average_rating);
CREATE INDEX idx_views ON listings(views);
CREATE INDEX idx_created_at ON listings(created_at);

-- Pivot Indexes
CREATE INDEX idx_category_id ON listing_category(category_id);
CREATE INDEX idx_listing_category ON listing_category(listing_id, category_id);

-- Prices Indexes
CREATE INDEX idx_listing_price ON listing_prices(listing_id, price);

-- Features Indexes
CREATE INDEX idx_listing_features ON listing_features(listing_id, feature_id);

-- Favorites Indexes
CREATE INDEX idx_user_listing ON favorites(user_id, listing_id);

-- Reviews Indexes
CREATE INDEX idx_listing_user ON reviews(listing_id, user_id);
SQL,
];
