<?php

// Use require (not require_once): route files are re-included on every
// application boot. require_once would register routes only on the first boot
// of a process, leaving later boots (e.g. each test) without API routes.
require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/areas.php';
require __DIR__ . '/api/cities.php';
require __DIR__ . '/api/authorities.php';
require __DIR__ . '/api/reports.php';
require __DIR__ . '/api/media.php';
require __DIR__ . '/api/comments.php';
require __DIR__ . '/api/representative-notes.php';
require __DIR__ . '/api/notifications.php';
require __DIR__ . '/api/stats.php';
require __DIR__ . '/api/users.php';
