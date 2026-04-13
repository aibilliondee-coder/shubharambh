<?php
/**
 * Popup "Book Your Interest" modal submission handler.
 * Thin wrapper — forces source='popup' and delegates to the same logic
 * used by contact_submit.php.
 */

$_POST['source'] = 'popup';
require __DIR__ . '/contact_submit.php';
