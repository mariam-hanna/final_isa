<?php

class Application_Model_Quiz extends Zend_Db_Table_Abstract 
{
    protected $_name = 'quiz';
    
    function diplayquiz() { 
        $select = $this->select()->from('quiz')->where("story_path='".$_SESSION['story']."'");
        return $this->fetchAll($select)->toArray();
        
    }
    
    
    function makeRand($answers){
        $randIndex = -1;
        $rand = array(-1);
        $randomAnswers = array();
   
        $correctAnswers = split(",", $answers[0]['answers']);
        
        for ($i = 0; $i < count($correctAnswers); $i++) {
            while (in_array($randIndex, $rand)) {
                $randIndex = rand(0, count($correctAnswers) - 1);
            }
            array_push($randomAnswers,$correctAnswers[$randIndex]);
            array_push($rand, $randIndex);
        }
        
        return $randomAnswers;
    }



    function addQuiz($questions,$answers,$story_path) {
        if ($questions['quizType'] == 'arrange'){
            $userAnswers = "";
            $row = $this->createRow();
            $row->story_path = $story_path;
            $row->question = $questions['q1'];
            
            foreach ($answers as $answer){
                $userAnswers .= $answer.",";
            }
            
            $row->answers = substr($userAnswers, 0, -1);
            $row->save();
        }
        
        elseif($questions['quizType'] == 'choice'){
            $row1 = $this->createRow();
            $row1->story_path = $story_path;
            $row1->question = $questions['q1'];
            $row1->answers = $answers[0].",".$answers[1].",".$questions['correct1'];
            $row1->save();
  
            $row2 = $this->createRow();
            $row2->story_path = $story_path;
            $row2->question = $questions['q2'];
            $row2->answers = $answers[2].",".$answers[3].",".$questions['correct2'];
            $row2->save();
        }
    }
    
}

