<?php

/**
 * Language pick website.
 * 
 * @author Bartosz Studnik
 */
class WelcomeView {
    
    /**
     * Method that prints header and main body of website.
     * 
     * @param string
     */
    public function __construct() {
        ?>
        <!DOCTYPE HTML>
        <html lang="pl">
        <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />

                <title>e-parchment.net</title>

                <meta name="description" content="Opis w Google" />
                <meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

                <link rel="stylesheet" href="../media/css/style_welcome.css" type="text/css" />
                <link href='https://fonts.googleapis.com/css?family=Caveat:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
                <link href='https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
        </head>
        <body>
            <div id="container">
                <div id="top">
                        <span style="margin-left: -5%;">e-parchment.net</span>
                </div>
                <div id="background">
                    <img src="../media/images/banner.png"/>
                    <div id="select">
                            Wybierz swój język:</br>
                            <span style="margin-left: -15%;">Please, select your language:</span>
                    </div>
                    <div id="flags">
                        <a href="index.php?lang=pl">
                            <div id="pl">
                                    <img src="../media/images//pl.png"/>
                            </div>
                        </a>
                        <a href="index.php?lang=en">
                            <div id="en">
                                    <img src="../media/images//en.png"/>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
