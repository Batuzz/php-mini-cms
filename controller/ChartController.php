<?php
require_once('view/ChartView.php');
require_once('DatabaseController.php');
require_once('model/Navigation.php');

/**
 * @author Bartosz Studnik
 */
class ChartController {
    
    private $dbConnection;
    
    public function __construct() {
        
        $panel = new ChartView();
        $this->dbConnection = new DatabaseController();
        $navigation = new Navigation($this->dbConnection->selectPages(), $this->dbConnection->selectMenu());
        $panel->header(INFO, $navigation->getNav());
        $tmp = $this->myContent();
        $panel->additionalContent('<table class="optTable" border="1" style="margin-left: auto; margin-right: auto;">
                        <tr><th>'.CHART_NUMBER.'</th><th>'.CHART_CONTENT.'</th><th>'.CHART_YOUR_ANSWER.'</th></tr>
                    <tr>'.$tmp.'</tr></table>',INFO);
        $panel->content($this->parseResearches($this->dbConnection->selectResearches()));
        $panel->footer();
    }
    
    private function parseResearches($result) {
        $resArray = array();
        while($res = $result->fetch()) {
            $resArray[$res["questionid"]][$res["answer"]] = $res["a"];
        }
        return $resArray;
    }
    
    private function myContent() {
        $toReturn = '';
        $result = $this->dbConnection->selectQuestions();
        $i = 1;
        if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
            while ($quiz = $result->fetch()) {
                if (isset($_SESSION['answer'.$i])) {
                    $toReturn .= '<tr><td>' . $quiz['id'] . '</td><td>'.$quiz['contenten'].'</td><td><img style="height: 50px; width: 60px" src="media/images/quiz_images/'.$quiz['answer' . $_SESSION['answer' . $i]] . '"><br />'.ANSWER.$_SESSION['answer'.$i].'</td> </tr>';
                }
                $i++;
            }
        } else {
            while ($quiz = $result->fetch()) {
                if (isset($_SESSION['answer'.$i])) {
                    $toReturn .= '<tr><td>' . $quiz['id'] . '</td><td>' . $quiz['content'] . '</td><td><img style="height: 50px; width: 60px" src="media/images/quiz_images/' . $quiz['answer' . $_SESSION['answer' . $i]] . '"><br />'.ANSWER.$_SESSION['answer'.$i].'</td> </tr>';
                }
                $i++;
            }
        }
        return $toReturn;
    }
}