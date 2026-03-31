<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig, Csrf};
$pageTitle = $pageTitle ?? SistemaConfig::metaTitle();
$metaDesc = $metaDesc ?? SistemaConfig::metaDescription();
$ogImage = $ogImage ?? SistemaConfig::ogImage();
$canonical = $canonical ?? SistemaConfig::url() . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<!DOCTYPE html>
<html lang="<?php echo View::e(I18n::idioma()); ?>">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo View::e($pageTitle); ?></title>
<meta name="description" content="<?php echo View::e($metaDesc); ?>"/>
<link rel="canonical" href="<?php echo View::e($canonical); ?>"/>

<!-- Open Graph -->
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?php echo View::e($pageTitle); ?>"/>
<meta property="og:description" content="<?php echo View::e($metaDesc); ?>"/>
<meta property="og:image" content="<?php echo View::e($ogImage); ?>"/>
<meta property="og:url" content="<?php echo View::e($canonical); ?>"/>
<meta property="og:site_name" content="<?php echo View::e(SistemaConfig::nome()); ?>"/>

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?php echo View::e($pageTitle); ?>"/>
<meta name="twitter:description" content="<?php echo View::e($metaDesc); ?>"/>
<meta name="twitter:image" content="<?php echo View::e($ogImage); ?>"/>

<!-- Favicon -->
<link rel="icon" href="<?php echo View::e(SistemaConfig::favicon()); ?>" type="image/x-icon"/>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet"/>

<!-- CSS -->
<link rel="stylesheet" href="/assets/css/lexus.css?v=<?php echo time(); ?>"/>

<!-- CSRF Meta -->
<meta name="csrf-token" content="<?php echo View::e(Csrf::gerar()); ?>"/>
</head>
<body>
