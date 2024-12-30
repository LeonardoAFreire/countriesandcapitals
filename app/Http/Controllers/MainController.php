<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        // load app_data.php file from app folder
        $this->app_data = require(app_path('app_data.php'));
    }

    public function startGame(): View
    {
        return view('home');
    }


    public function prepareGame(Request $request)
    {
        // validade request
        $request->validate([
            'total_questions' => 'required|integer|min:3|max:30'
        ], [
            'total_questions.required' => 'O numero de perguntas é obrigatório',
            'total_questions.integer' => 'O numero de perguntas deve ser um valor inteiro',
            'total_questions.min' => 'O numero de perguntas deve ser pelo menos :min',
            'total_questions.max' => 'O numero de perguntas deve ser no máximo :max'
        ]);

        // get total_questions from request
        $total_questions = intval($request->input('total_questions'));

        // prepare all the quiz structure
        $quiz = $this->prepareQuiz($total_questions);

        dd($quiz);
    }

    private function prepareQuiz($total_questions)
    {
        $questions = [];

        $total_countries = count($this->app_data);

        // create countries index for unique questions
        $indexes = range(0, $total_countries - 1);
        shuffle($indexes);
        $indexes = array_slice($indexes, 0, $total_questions);

        // create array of questions
        $question_number = 1;
        foreach ($indexes as $index) {
            $question['question_number'] = $question_number++;
            $question['country'] = $this->app_data[$index]['country'];
            $question['correct_answer'] = $this->app_data[$index]['capital'];

            // wrong answers
            $other_capitals = array_column($this->app_data, 'capital');
            // remove correct answer
            $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);
            // shuffle wrong answers
            shuffle($other_capitals);
            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);

            // store answer result
            $question['correct'] = null;
            $questions[] = $question;
        }
        return $questions;
    }
}
