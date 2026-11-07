<?php
/**
 * Foliioo Design System V1.2 — Standard Head Include (Logo Palette Edition)
 */
$pageTitle = $pageTitle ?? 'Foliioo - Modern Event Lifecycle Platform';

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string {
        static $base = null;
        if ($base === null) {
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $base = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
        }
        if ($path === '' || $path === '/') {
            return $base ?: '/';
        }
        return $base . '/' . ltrim($path, '/');
    }
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>

<!-- Google Fonts: DM Sans, Fraunces, Cormorant Garamond, DM Serif Display -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=DM+Serif+Display:ital@0;1&family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&display=swap" rel="stylesheet">

<!-- Font Awesome 6.7.2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        colors: {
          coral: {
            DEFAULT: '#FF502B',
            dark: '#E03A17',
            light: '#FFF0ED'
          },
          terracotta: {
            DEFAULT: '#FF502B',
            dark: '#E03A17'
          },
          cream: '#FAFBF6',
          paper: '#FFFFFF',
          tint: '#F3F4EE',
          deep: '#0D0D0D',
          sage: {
            DEFAULT: '#4C7C59',
            light: '#EEF5F0'
          },
          ink: '#0F0F0F',
          muted: '#63615E',
          soft: '#A6A39D',
          warm: {
            border: '#EAE8E2',
            borderDark: '#D8D6CE'
          },
          status: {
            success: '#2E7D47',
            'success-bg': '#EBF5EE',
            warning: '#B45309',
            'warning-bg': '#FEF3C7',
            error: '#DC2626',
            'error-bg': '#FEE2E2'
          }
        },
        fontFamily: {
          sans: ['"DM Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
          display: ['"Fraunces"', 'Georgia', 'serif'],
          elegant: ['"Cormorant Garamond"', 'Georgia', 'serif'],
          floral: ['"DM Serif Display"', 'Georgia', 'serif']
        },
        boxShadow: {
          card: '0 8px 30px rgba(15, 15, 15, 0.05)'
        }
      }
    }
  }
</script>

<!-- Design System Global & Component Styles -->
<link rel="stylesheet" href="<?= base_url('/css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('/css/components.css') ?>">

<?php if (!empty($extraHead)) echo $extraHead; ?>
