<?php
/**
 * Site-wide settings loader.
 * Caches the single-row site_settings table for the current request.
 */

if (!function_exists('db')) {
    require_once __DIR__ . '/db.php';
}

function load_settings(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $defaults = [
        'id'              => 1,
        'company_name'    => defined('DEFAULT_COMPANY_NAME') ? DEFAULT_COMPANY_NAME : 'Shubharambh Infra Advisors',
        'tagline'         => 'Your Success Our Priority',
        'phone_primary'   => defined('DEFAULT_PHONE') ? DEFAULT_PHONE : '+91 9911600100',
        'phone_whatsapp'  => defined('DEFAULT_WHATSAPP') ? DEFAULT_WHATSAPP : '919911600100',
        'email_primary'   => defined('DEFAULT_EMAIL') ? DEFAULT_EMAIL : 'company@shubharambhinfraadvisors.com',
        'email_secondary' => 'support@shubharambhinfraadvisors.com',
        'address_line'    => 'B-220, Logix Technova, Sector 132, Noida – 201304, Uttar Pradesh, India',
        'map_embed_url'   => '',
        'rera_number'     => 'UP RERA: Coming Soon',
        'rera_notice'     => '',
        'facebook_url'    => '',
        'instagram_url'   => '',
        'linkedin_url'    => '',
        'youtube_url'     => '',
        'twitter_url'     => '',
        'hero_title'      => 'Find Your Luxury Home',
        'hero_subtitle'   => 'BEST REAL ESTATE PROPERTY CONSULTANT IN DELHI/NCR',
        'about_heading'   => 'Get To Know About Shubharambh Infra',
        'about_body'      => '',
    ];

    try {
        $stmt = db()->query('SELECT * FROM site_settings WHERE id = 1 LIMIT 1');
        $row  = $stmt->fetch();
        $cache = $row ? array_merge($defaults, $row) : $defaults;
    } catch (Throwable $e) {
        // Fail open with defaults so a missing DB doesn't blank the site.
        $cache = $defaults;
    }

    return $cache;
}
