<?php
require_once('controller/IndexController.php');
require_once('controller/WelcomeController.php');
require_once('controller/ChartController.php');

/**
 * It's responsible for picking right language (WelcomeController) and setting proper sites' `title`, `content` and `header` (calls IndexController constructor).
 */

if(!isset($_SESSION['lang']) && !isset($_GET['lang'])) {
    $panel = new WelcomeController(); 
} elseif(isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header("Location: index.php");
} else {
    $contentArray = array(
        'index' => array(
            'title' => INDEX_TITLE,
            'content' => INDEX_CONTENT,
            'header' => INDEX_HEADER),
        'researches' => array(
            'title' => RESEARCHES_HEADER,
            'content' => RESEARCHES_CONTENT,
            'header' => RESEARCHES_HEADER),
        'about' => array(
            'title' => ABOUT_TITLE, 
            'content' => ABOUT_CONTENT, 
            'header' => ABOUT_HEADER),
    );

    if(isset($_GET['p']) && $_GET['p'] == 'researches') {
        $chart = new ChartController();
        exit;
    } elseif(isset($_GET['p']) && $_GET['p'] == 'about') {
        $page = 'about';
    } else {
        $page = 'index';
    }
    
    $index = new IndexController($contentArray[$page]['title'], $contentArray[$page]['content'], $contentArray[$page]['header']);
}