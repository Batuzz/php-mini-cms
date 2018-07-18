<?php
require_once('PageView.php');

/**
 * @author Bartosz Studnik
 */
class AdminLoginView extends PageView {
    
    public function content($content = null) {
        ?>
                <header>
                    <h1>Admin Panel</h1>
                </header>
                <article>
                    <p><?=$content;?></p>
                </article>
        <?php
    }
    
}
