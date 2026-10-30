<?php
declare(strict_types=1);

class MockData {
    private static string $dataDir = __DIR__ . '/../js/data';

    private static function loadJson(string $fileName): array {
        $filePath = self::$dataDir . '/' . $fileName . '.json';
        if (!is_file($filePath)) {
            return [];
        }
        $contents = file_get_contents($filePath);
        return $contents !== false ? (json_decode($contents, true) ?? []) : [];
    }

    public static function getEventTypes(): array {
        return self::loadJson('event_types');
    }

    public static function getPlans(): array {
        return self::loadJson('plans');
    }

    public static function getPlanById(string $planId): ?array {
        foreach (self::getPlans() as $plan) {
            if ($plan['id'] === $planId) {
                return $plan;
            }
        }
        return null;
    }

    public static function getEvents(): array {
        return self::loadJson('events');
    }

    public static function getEventById(string $eventId): ?array {
        foreach (self::getEvents() as $event) {
            if ($event['id'] === $eventId) {
                return $event;
            }
        }
        return null;
    }

    public static function getEventBySlug(string $slug): ?array {
        foreach (self::getEvents() as $event) {
            if ($event['slug'] === $slug) {
                return $event;
            }
        }
        return null;
    }

    public static function getProgrammesForEvent(string $eventId): array {
        $all = self::loadJson('programmes');
        $filtered = array_filter($all, fn(array $item) => $item['event_id'] === $eventId);
        usort($filtered, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        return array_values($filtered);
    }

    public static function getGuestsForEvent(string $eventId): array {
        $all = self::loadJson('guests');
        return array_values(array_filter($all, fn(array $item) => $item['event_id'] === $eventId));
    }

    public static function getAttendanceStats(string $eventId): array {
        $guests = self::getGuestsForEvent($eventId);
        $total = count($guests);
        $attending = 0;
        $reminders = 0;
        $checkedIn = 0;

        foreach ($guests as $g) {
            if (($g['rsvp_status'] ?? '') === 'attending') {
                $attending += ($g['party_size'] ?? 1);
            }
            if (!empty($g['reminder_subscribed'])) {
                $reminders++;
            }
            if (!empty($g['checked_in'])) {
                $checkedIn++;
            }
        }

        return [
            'total_registered' => $total,
            'total_attending_seats' => $attending,
            'reminder_subscribers' => $reminders,
            'checked_in_count' => $checkedIn,
            'check_in_rate_percentage' => $total > 0 ? round(($checkedIn / $total) * 100) : 0,
        ];
    }

    public static function getCreatorById(string $creatorId): ?array {
        $creators = self::loadJson('creators');
        foreach ($creators as $creator) {
            if ($creator['id'] === $creatorId) {
                return $creator;
            }
        }
        return null;
    }
}
