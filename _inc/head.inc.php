
<?php
$pageTitle = $pageTitle ?? 'Foliioo — Your Event, Beautifully Told';
$pageDescription = $pageDescription ?? 'Create a beautiful event website, manage your guests, and bring every special moment together with Foliioo.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="theme-color" content="#FAF8F5">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Google Fonts: editorial headings + clean interface text -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Cormorant+Garamond:wght@500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Foliioo Design System -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        foliioo: {
                            cream: '#FAF8F5',
                            paper: '#FFFFFF',
                            ink: '#252321',
                            muted: '#6B675F',
                            border: '#E9E4DD',
                            accent: '#9A5F44',
                            'accent-dark': '#7E4A33',
                            'accent-light': '#E0A98C',
                            sage: '#87927B',
                            'sage-dark': '#59644F',
                            'sage-light': '#EEF0EA',
                            tint: '#F3EDE5',
                            deep: '#252321',
                            soft: '#A19B93',
                            success: '#3F6B48',
                            'success-bg': '#E8F1EA',
                            warning: '#8F5F12',
                            'warning-bg': '#FBF1DC',
                            error: '#B54747',
                            'error-bg': '#F8E6E6'
                        }
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Fraunces', 'Georgia', 'serif'],
                        'tpl-elegant': ['Cormorant Garamond', 'Georgia', 'serif'],
                        'tpl-floral': ['DM Serif Display', 'Georgia', 'serif']
                    },
                    boxShadow: {
                        soft: '0 8px 30px rgba(37, 35, 33, 0.06)'
                    }
                }
            }
        };
    </script>

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/components.css">

    <script src="js/auth/auth.js" defer></script>
    <script src="js/app.js" defer></script>
</head>
<body class="bg-foliioo-cream text-foliioo-ink font-sans antialiased">
