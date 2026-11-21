<?php

return [
    // common lang key
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',

    // notifications.
    'notification' => [
        'success' => [
            'default_title' => 'Sucess',
            'default_body' => 'Action Completed Successfully.',
        ],
    ],

    // category
    'category' => [
        'navigation' => [
            'label' => 'Category',
            'model_label' => 'Category',
            'plural_model_label' => 'Category',
        ],
        'fields' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'parent_category' => 'Parent',
            'description' => 'Description',
            'status' => 'Status',
        ],
        'action' => [
            'toggle' => 'Toggle',
        ],
    ],
    // post resource
    'post' => [
        'navigation' => [
            'label' => 'Post',
            'model_label' => 'Post',
            'plural_model_label' => 'Post',
        ],
        'filters' => [
            'status' => 'Filter By Status',
        ],
        'fields' => [
            'type' => 'Type',
            'title' => 'Title',
            'slug' => 'Slug',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
            'feature_image' => 'Image',
            'is_featured' => 'Featured',
            'comment_status' => 'Comment Status',
            'status' => 'Status',
            'published_at' => 'Published At',
            'parent_id' => 'Parent',
            'author' => 'Author',
            'view_count' => 'Views',
            'categories' => 'Categories',
            'attachments' => 'Attachments',
        ],
    ],

    // page resource
    'page' => [
        'navigation' => [
            'label' => 'Page',
            'model_label' => 'Page',
            'plural_model_label' => 'Page',
        ],
    ],

    // Menu resource
    'menu' => [
        'navigation' => [
            'label' => 'Menu',
            'model_label' => 'Menu',
            'plural_model_label' => 'Menu',
        ],
    ],

    // User resource
    'user' => [
        'navigation' => [
            'label' => 'User',
            'model_label' => 'User',
            'plural_model_label' => 'User',
        ],
    ],

    // Comment resource
    'comment' => [
        'navigation' => [
            'label' => 'Comment',
            'model_label' => 'Comment',
            'plural_model_label' => 'Comment',
        ],
    ],

    // Gallery resource
    'gallery' => [
        'navigation' => [
            'label' => 'Gallery',
            'model_label' => 'Gallery',
            'plural_model_label' => 'Gallery',
        ],
    ],

    // Image resource
    'image' => [
        'navigation' => [
            'label' => 'Image',
            'model_label' => 'Image',
            'plural_model_label' => 'Image',
        ],
    ],

];
