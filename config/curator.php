<?php

return [
    'accepted_file_types' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/svg+xml',
        'application/pdf',
    ],
    'cloud_disks' => [
        's3',
        'cloudinary',
        'imgix',
    ],
    'cropper' => [
        'check_cross_origin' => true,
    ],
    'curation_formats' => [
        'webp',
    ],
    'curation_presets' => [
        \Awcodes\Curator\Curations\ThumbnailPreset::class,
    ],
    'directory' => 'media',
    'disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
    'glide' => [
        'server' => \Awcodes\Curator\Glide\DefaultServerFactory::class,
        'fallbacks' => [],
        'route_path' => 'curator',


'params' => [
            'q' => '80',      // الجودة 80% لتقليل الحجم مع الحفاظ على النقاء
            'fm' => 'webp',   // تحويل كافة المخرجات لـ WebP تلقائياً
        ],


    ],
    'image_crop_aspect_ratio' => null,
    'image_resize_mode' => null,
    'image_resize_target_height' => 1080,
    'image_resize_target_width' => 1080,
    'is_limited_to_directory' => false,
    'is_tenant_aware' => false,
    'tenant_ownership_relationship_name' => 'tenant',
    'max_size' => 102400,
    'model' => \Awcodes\Curator\Models\Media::class,
    'min_size' => 0,
    'path_generator' => null,
    'resources' => [
        'label' => 'Media',
        'plural_label' => 'Media',
        'navigation_group' => null,
        'cluster' => null,
        'navigation_label' => 'Media',
        'navigation_icon' => 'heroicon-o-photo',
        'navigation_sort' => null,
        'navigation_count_badge' => false,
        'resource' => \Awcodes\Curator\Resources\MediaResource::class,
    ],
    'should_preserve_filenames' => false,
    'should_register_navigation' => true,
    'should_check_exists' => true,
    'visibility' => 'public',
    'tabs' => [
        'display_curation' => true,
        'display_upload_new' => true,
    ],
    'multi_select_key' => 'metaKey',
    'table' => [
        'layout' => 'grid',
    ],






];
