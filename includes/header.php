<?php $pageTitle = $pageTitle ?? 'Café Song Request'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: { extend: {
        fontFamily: { serif: ['"Playfair Display"', 'serif'], sans: ['Inter', 'sans-serif'] },
        colors: {
          cream: '#FAF3E8', latte: '#E8D5B7', mocha: '#6F4E37',
          espresso: '#3B2A20', caramel: '#C68B59'
        }
      }}
    }
  </script>
</head>
<body class="bg-cream text-espresso font-sans min-h-screen">