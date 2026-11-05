<?php
/**
 * Shared state values and mock configuration for Ghost-owned modules.
 */

declare(strict_types=1);

const GHT_EVENT_PUBLICATIONS = ['draft', 'published', 'unpublished'];
const GHT_EVENT_PHASES = ['pre_event', 'live', 'post_event'];
const GHT_EVENT_PHASE_LABELS = [
    'pre_event' => 'Pre-Event',
    'live' => 'Live',
    'post_event' => 'Post-Event',
];
const GHT_EVENT_PHASE_GUEST_IMPACTS = [
    'pre_event' => 'Guests see the event details, RSVP options, and preparations for the day.',
    'live' => 'Guests see that the event is happening now, along with live programme updates.',
    'post_event' => 'Guests see the event as completed and can revisit shared memories.',
];
const GHT_EVENT_EXCEPTIONS = ['none', 'postponed', 'cancelled'];
const GHT_PROGRAMME_STATUSES = ['upcoming', 'in_progress', 'completed'];
const GHT_PROGRAMME_MODES = ['controlled', 'automatic'];
const GHT_GUEST_RESPONSES = ['attending', 'declined', 'pending'];
const GHT_CHECK_IN_STATUSES = ['not_arrived', 'checked_in'];
const GHT_MEDIA_ALBUMS = ['pre_event', 'post_event'];
const GHT_PAYMENT_STATUSES = ['successful', 'pending', 'failed'];
const GHT_NOTIFICATION_TYPES = ['postponement', 'cancellation', 'event_update'];
const GHT_NOTIFICATION_CHANNELS = ['email', 'sms'];
const GHT_NOTIFICATION_STATUSES = ['simulated', 'sent', 'failed'];
const GHT_FEATURE_KEYS = ['rsvp', 'tickets', 'donations', 'gallery_pre', 'gallery_post'];
const GHT_FEATURE_LABELS = [
    'rsvp' => 'RSVPs',
    'tickets' => 'Tickets and table payments',
    'donations' => 'Donations',
    'gallery_pre' => 'Pre-event gallery',
    'gallery_post' => 'Post-event gallery',
];
const GHT_MOCK_NOTIFICATION_FEE = 2500;
const GHT_DASHBOARD_STATUS_FILTERS = [
    'draft',
    'published',
    'live',
    'completed',
    'postponed',
    'cancelled',
    'unpublished',
];
const GHT_MONEY_CURRENCY = 'NGN';
const GHT_MONEY_UNIT = 'naira';
const GHT_PROGRAMME_POLL_INTERVAL_SECONDS = 7;
const GHT_DATA_FILES = [
    'events.json',
    'programme.json',
    'guests.json',
    'media.json',
    'donations.json',
    'orders.json',
    'notifications.json',
    'plans.json',
];
