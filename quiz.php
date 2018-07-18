<?php
require_once('controller/ResearchController.php');
require_once('controller/QuestionController.php');
require_once('controller/DatabaseController.php');
require_once('model/Research.php');

/**
 * It's responsible for checking if any question answer was sent. If so, adds all quiz's data to database.
 * If `nick`, `age` and `gender` was set properly calls QuestionController constructor.
 */

if(isset($_POST['q1']) || isset($_POST['q2']) || isset($_POST['q3']) || isset($_POST['q4']) || isset($_POST['q5']) || isset($_POST['q6']) || isset($_POST['q7']) || isset($_POST['q8']) || isset($_POST['q9']) || isset($_POST['q10'])) {
    
    $dbConnection = new DatabaseController();
    for($num = 1; $num < 11; $num++) {
        if(isset($_POST['q'.$num]) && $_POST['q'.$num] > 0) {
            
            $research = new Research($dbConnection);
            $research->setResearch($_SESSION['quizGender'], $_SESSION['quizAge'], $_SESSION['quizNick'], $num, $_POST['q'.$num]);
            $research->insert();
            $_SESSION['answer'.$num] = $_POST['q'.$num];
        }
    }
    header("Location: researches.html");
}

if(!isset($_POST['nick']) || (strlen($_POST['nick']) <= 0) || !isset($_POST['age']) || !(is_numeric($_POST['age'])) || !($_POST['age'] < 130) || !isset($_POST['gender']) || $_POST['gender'] == '-') {
    $quiz = new ResearchController();
    
} else {
    $_SESSION['quizNick'] = $_POST['nick'];
    $_SESSION['quizAge'] = $_POST['age'];
    $_SESSION['quizGender'] = $_POST['gender'];
    $questions = new QuestionController();
}