<?php
$pageTitle = $pageTitle ?? 'Foliioo - Modern Event Lifecycle Platform';
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Neue Montreal"', 'Arial', 'sans-serif'],
            serif: ['Playfair Display', 'serif']
          },
          colors: {
            foliioo: {
              completed: '#0D160B',
              accent: '#320E3B',
              accentHover: '#4A1556'
            }
          }
        }
      }
    }
  </script>

  <style>
    html, body {
      overflow-x: hidden;
      max-width: 100%;
    }
    body {
      font-family: 'Neue Montreal', Arial, sans-serif;
      background-color: #F8FAFC;
      color: #0F172A;
    }
    html {
      scroll-behavior: smooth;
    }
    .glass-island {
      background: rgba(255, 255, 255, 0.45);
      backdrop-filter: blur(20px) saturate(180%);
      -webkit-backdrop-filter: blur(20px) saturate(180%);
      border: 1px solid rgba(255, 255, 255, 0.55);
      box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08), 0 0 15px rgba(50, 14, 59, 0.03);
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>

  <?php if (!empty($extraHead)) echo $extraHead; ?>
</head>
<body class="h-full flex flex-col font-sans text-gray-900 antialiased selection:bg-[#320E3B] selection:text-white w-full max-w-full overflow-x-hidden">
