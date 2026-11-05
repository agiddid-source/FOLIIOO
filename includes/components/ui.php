<?php
/**
 * Reusable Foliioo UI primitives: buttons, fields, status badges, alerts, and empty states.
 * Includes: data.php escaping helper at call time. Reads and writes no data.
 */

declare(strict_types=1);

/**
 * Builds a shared button class list for the approved button variants.
 */
function ghtComponentButtonClasses(string $variant = 'primary', string $size = 'md'): string
{
    $variants = [
        'primary' => 'bg-[#9A5F44] text-white hover:bg-[#7E4A33]',
        'secondary' => 'border border-[#E9E4DD] bg-white text-[#252321] hover:bg-[#FAF8F5]',
        'dark' => 'bg-[#252321] text-white hover:bg-[#3D3A37]',
        'ghost' => 'bg-transparent text-[#6B675F] hover:bg-[#F3EDE5] hover:text-[#252321]',
        'danger' => 'bg-[#B54747] text-white hover:bg-[#963A3A]',
    ];
    $sizes = [
        'sm' => 'min-h-11 px-3 py-2 text-sm',
        'md' => 'min-h-11 px-4 py-2.5 text-sm',
        'lg' => 'min-h-12 px-5 py-3 text-base',
    ];
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];

    return 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition duration-200 '
        . 'active:translate-y-px focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 '
        . 'focus-visible:outline-[#9A5F44] ' . $variantClasses . ' ' . $sizeClasses;
}

/**
 * Renders an escaped, labelled form control.
 *
 * @param array<string, string> $attributes
 */
function ghtComponentField(
    string $id,
    string $label,
    string $value = '',
    string $type = 'text',
    string $help = '',
    array $attributes = []
): string {
    $allowedTypes = ['text', 'email', 'date', 'time', 'number', 'url', 'tel', 'search'];
    $inputType = in_array($type, $allowedTypes, true) ? $type : 'text';
    $attributeMarkup = '';

    foreach ($attributes as $name => $attributeValue) {
        if (!preg_match('/^(?:name|min|max|step|required|autocomplete|inputmode|placeholder|readonly|maxlength)$/', $name)) {
            continue;
        }

        if ($attributeValue === 'true') {
            $attributeMarkup .= ' ' . $name;
            continue;
        }

        $attributeMarkup .= ' ' . $name . '="' . ghtEscape($attributeValue) . '"';
    }

    $field = '<div class="space-y-1.5">'
        . '<label class="block text-sm font-semibold text-[#252321]" for="' . ghtEscape($id) . '">'
        . ghtEscape($label) . '</label>'
        . '<input class="min-h-11 w-full rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm text-[#252321] '
        . 'focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20" id="'
        . ghtEscape($id) . '" type="' . $inputType . '" value="' . ghtEscape($value) . '"'
        . $attributeMarkup . '>';

    if ($help !== '') {
        $field .= '<p class="text-xs leading-5 text-[#6B675F]">' . ghtEscape($help) . '</p>';
    }

    return $field . '</div>';
}

/**
 * Renders an event-state badge with both a text label and an icon.
 */
function ghtComponentStatusBadge(string $status): string
{
    $presentation = ghtGetEventStatusPresentation($status);

    return '<span class="inline-flex min-h-8 items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold '
        . ghtEscape($presentation['classes']) . '"><i class="fa-solid '
        . ghtEscape($presentation['icon']) . '" aria-hidden="true"></i>'
        . ghtEscape($presentation['label']) . '</span>';
}

/**
 * Renders an accessible feedback alert.
 */
function ghtComponentAlert(string $message, string $type = 'info'): string
{
    $styles = [
        'success' => ['classes' => 'bg-[#E8F1EA] text-[#3F6B48]', 'icon' => 'fa-circle-check', 'role' => 'status'],
        'warning' => ['classes' => 'bg-[#FBF1DC] text-[#8F5F12]', 'icon' => 'fa-triangle-exclamation', 'role' => 'status'],
        'error' => ['classes' => 'bg-[#F8E6E6] text-[#8F3939]', 'icon' => 'fa-circle-exclamation', 'role' => 'alert'],
        'info' => ['classes' => 'bg-[#EEF0EA] text-[#59634F]', 'icon' => 'fa-circle-info', 'role' => 'status'],
    ];
    $style = $styles[$type] ?? $styles['info'];

    return '<div class="flex items-start gap-3 rounded-lg px-4 py-3 text-sm leading-5 '
        . $style['classes'] . '" role="' . $style['role'] . '"><i class="fa-solid '
        . $style['icon'] . ' mt-0.5" aria-hidden="true"></i><p>' . ghtEscape($message) . '</p></div>';
}

/**
 * Renders a reusable empty state with an optional safe internal action link.
 */
function ghtComponentEmptyState(
    string $title,
    string $description,
    string $icon = 'fa-folder-open',
    string $actionLabel = '',
    string $actionHref = ''
): string {
    $content = '<section class="rounded-xl border border-[#E9E4DD] bg-white px-6 py-10 text-center">'
        . '<span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#EEF0EA] text-[#59634F]" aria-hidden="true">'
        . '<i class="fa-solid ' . ghtEscape($icon) . ' text-lg"></i></span>'
        . '<h2 class="mt-4 font-display text-xl font-semibold">' . ghtEscape($title) . '</h2>'
        . '<p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#6B675F]">' . ghtEscape($description) . '</p>';

    if ($actionLabel !== '' && $actionHref !== '') {
        $content .= '<a class="' . ghtEscape(ghtComponentButtonClasses('secondary')) . ' mt-5" href="'
            . ghtEscape($actionHref) . '">' . ghtEscape($actionLabel) . '</a>';
    }

    return $content . '</section>';
}

/**
 * Renders a shared confirmation dialog. Forms can target it with data-confirm.
 */
function ghtComponentConfirmationDialog(): string
{
    return '<dialog class="ght-confirm-dialog w-[min(92vw,28rem)] rounded-2xl border border-[#E9E4DD] bg-white p-0 text-[#252321] shadow-[0_8px_30px_rgba(37,35,33,0.16)] backdrop:bg-[#252321]/50">'
        . '<form method="dialog" class="p-6"><p class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Please confirm</p>'
        . '<h2 class="ght-confirm-title mt-2 font-display text-xl font-semibold">Confirm this action</h2>'
        . '<p class="ght-confirm-message mt-2 text-sm leading-6 text-[#6B675F]"></p>'
        . '<div class="mt-6 flex justify-end gap-3"><button class="'
        . ghtEscape(ghtComponentButtonClasses('secondary')) . '" value="cancel">Go back</button>'
        . '<button class="' . ghtEscape(ghtComponentButtonClasses('primary')) . '" value="confirm">Confirm</button>'
        . '</div></form></dialog>';
}
