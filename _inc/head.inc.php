<?php
/**
 * Foliioo Design System V1.1 — Standard Head Include
 */
$pageTitle = $pageTitle ?? 'Foliioo - Modern Event Lifecycle Platform';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>

<!-- Google Fonts: DM Sans, Fraunces, Cormorant Garamond, DM Serif Display -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=DM+Serif+Display:ital@0;1&family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&display=swap" rel="stylesheet">

<!-- Font Awesome 6.7.2 (Strict Design System Rule: FA 6.7.2 only) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        colors: {
          cream: '#FAF8F5',
          paper: '#FFFFFF',
          tint: '#F3EDE5',
          deep: '#252321',
          terracotta: {
            DEFAULT: '#9A5F44',
            dark: '#7E4A33'
          },
          sage: {
            DEFAULT: '#87927B',
            light: '#EEF0EA'
          },
          ink: '#252321',
          muted: '#6B675F',
          soft: '#A19B93',
          warm: {
            border: '#E9E4DD',
            borderDark: '#D8D1C8'
          },
          status: {
            success: '#3F6B48',
            'success-bg': '#E8F1EA',
            warning: '#8F5F12',
            'warning-bg': '#FBF1DC',
            error: '#B54747',
            'error-bg': '#F8E6E6'
          }
        },
        fontFamily: {
          sans: ['"DM Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
          display: ['"Fraunces"', 'Georgia', 'serif'],
          elegant: ['"Cormorant Garamond"', 'Georgia', 'serif'],
          floral: ['"DM Serif Display"', 'Georgia', 'serif']
        },
        boxShadow: {
          card: '0 8px 30px rgba(37, 35, 33, 0.06)'
        }
      }
    }
  }
</script>

<!-- Design System Global & Component Styles -->
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/components.css">

<?php if (!empty($extraHead)) echo $extraHead; ?>
