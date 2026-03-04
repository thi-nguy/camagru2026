<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <header class="header" id="header">
  <div class="header-logo" onclick="navTo('gallery')">Cam<span>agru</span></div>
  <nav class="header-nav" id="desktopNav">
    <button class="btn btn-ghost btn-sm" onclick="navTo('gallery')" id="navGallery">Gallery</button>
    <button class="btn btn-primary btn-sm" onclick="navTo('editor')" id="navEditor" style="display:none">Editor</button>
    <button class="btn btn-ghost btn-sm" onclick="navTo('auth')" id="navAuth">Sign In</button>
    <button class="btn btn-danger btn-sm" onclick="logout()" id="navLogout" style="display:none">Log Out</button>
  </nav>
  <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMobileMenu()">
    <span></span><span></span><span></span>
  </button>
</header>

<!-- Mobile menu -->
<div class="mobile-menu" id="mobileMenu">
  <button class="btn btn-ghost" onclick="navTo('gallery');toggleMobileMenu()">Gallery</button>
  <button class="btn btn-primary" onclick="navTo('editor');toggleMobileMenu()" id="mNavEditor" style="display:none">Editor</button>
  <button class="btn btn-ghost" onclick="navTo('auth');toggleMobileMenu()" id="mNavAuth">Sign In</button>
  <button class="btn btn-danger" onclick="logout();toggleMobileMenu()" id="mNavLogout" style="display:none">Log Out</button>
</div>
