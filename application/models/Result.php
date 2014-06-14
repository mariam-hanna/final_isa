<?php

class Application_Model_Result extends Zend_Db_Table_Abstract {

    protected $_name = 'result';

    function result($answers) {
        $result = "fail";
        if ($answers['quizType'] == 'choice') {
            $score = 0;

            for ($i = 1; $i <= (count($answers) - 3) / 2; $i++) {
                if ($answers['ans' . $i] == $answers['correct_ans' . $i]) {
                    $score++;
                }
            }


            if ($score >= (count($answers) - 3) / 4) {
                $result = "pass";
            }
        } 
        
        elseif ($answers['quizType'] == 'arrange') {
            $storyQuiz = new Application_Model_Quiz();
            $quiz = $storyQuiz->diplayquiz($_SESSION['story']);
            $correctAnswers = split(",", $quiz[0]['answers']);
            $userAnswers = array_values($answers);
            for ($i = 0; $i < count($correctAnswers); $i++) {
                if ($correctAnswers[$i] == $userAnswers[$i + 3]) {
                    $result = 'pass';
                } else {
                    $result = 'fail';
                    break;
                }
            }
        }
        if ($result === 'pass') {
            $story = new Application_Model_Story();
            $storyLevel = $story->getStoryLevel();

            $levels = new Application_Model_Result();
            $userLevel = $levels->getUserLevel();

            if ($userLevel == $storyLevel) {
                $row = $this->fetchRow($this->select()->where('user_email = ?', $_SESSION['email'])->where('cat_id = ?', $_SESSION['cat_id']));
                $row->level += 1;
                $row->save();
            }
        }
        return $result;
    }

    function getUserLevel() {
        $userLevel = null;
        $select = $this->select()->from('result')->where('user_email = ?', $_SESSION['email'])->where("cat_id =?", $_SESSION['cat_id']);
        $levels = $this->fetchAll($select)->toArray();
        foreach ($levels as $level) {
            $userLevel = $level['level'];
        }

        return $userLevel;
    }

    function signup($user_email) {
        $row1 = $this->createRow();
        $row1->user_email = $user_email;
        $row1->level = 1;
        $row1->cat_id = 1;
        $row1->save();

        $row2 = $this->createRow();
        $row2->user_email = $user_email;
        $row2->level = 1;
        $row2->cat_id = 2;
        $row2->save();
    }

}
