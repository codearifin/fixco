<?php
if(!isset($db)) include('dbc.php');

define('TRANSITION', serialize(array(1=>'random', 'premium-random', 'boxslide', 'boxfade', 'slotzoom-horizontal', 'slotzoom-vertical', 'slotslide-horizontal', 'slotslide-vertical', 'slotfade-horizontal', 'slotfade-vertical', 'curtain-1', 'curtain-2', 'curtain-3', 'slideleft', 'slideright', 'slideup', 'slidedown', 'slidehorizontal', 'slidevertical', 'fade', 'papercut', 'flyin', 'turnoff', 'cube', '3dcurtain-vertical', '3dcurtain-horizontal')));

define('TRANXITION', serialize(array(1=>'Random', 'Premium Random', 'Box Slide', 'Box Fade', 'Slotzoom Horizontal', 'Slotzoom Vertical', 'Slotslide Horizontal', 'Slotslide Vertical', 'Slotfade Horizontal', 'Slotfade Vertical', 'Curtain 1', 'Curtain 2', 'Curtain 3', 'Slide Left', 'Slide Right', 'Slide Up', 'Slide Down', 'Slide Horizontal', 'Slide Vertical', 'Fade', 'Papercut', 'Flyin', 'Turn Off', 'Cube', '3d-Curtain Vertical', '3d-Curtain Horizontal')));

define('EASING', serialize(array(1=>'easeInOutExpo', 'easeInOutQuad', 'easeInOutCubic', 'easeInOutQuart', 'easeInOutQuint', 'easeInOutSine', 'easeInOutCirc', 'easeInOutElastic', 'easeInOutBack', 'easeInOutBounce')));

define('KLASS', serialize(array(1=>'sft', 'sfb', 'sfr', 'sfl', 'lft', 'lfb', 'lfr', 'lfl', 'fade', 'randomrotate')));

define('CAPTION', serialize(array(1=>'Short from Top', 'Short from Bottom', 'Short from Right', 'Short from Left', 'Long from Top', 'Long from Bottom', 'Long from Right', 'Long from Left', 'Fading', 'Random')));
?>