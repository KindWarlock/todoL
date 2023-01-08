<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Task;


class TasksController extends Controller
{
    public function index() {
        if (array_key_exists('date', request()->all())) {
            $curr_date = request()->all()['date'];
        } elseif (session('date')) {
            $curr_date = session('date');
        } else {
            $curr_date = date('Y-m-d');
        }
        return redirect()->route('tasks.show', ['date' => $curr_date]);
    }


    public function show($date) {
        $types = \App\Models\Type::get();

        $tasks_current = Task::where('date', $date)->
            where('user_id', auth()->id())->
            where('is_done', '0')->
            get();
        $duration = 0;
        foreach ($tasks_current as $t) {
            $t->type = $t->type->name;
            $duration += $t->duration;
        }

        $tasks_done = Task::where('date', $date)->
        where('user_id', auth()->id())->
        where('is_done', '1')->
        get();
        foreach ($tasks_done as $t) {
            $t->type = $t->type->name;
        }
        

        session(['date' => $date]);

        return view('tasks', ['tasks_current'=>$tasks_current, 
            'tasks_done'=>$tasks_done,
            'types'=>$types, 
            'date'=>$date,
            'duration'=>$duration,
            'form' => session('form'),
            'id' => session('id')
        ]);
    }


    public function store() {
        // Чтобы не потерять введенные данные при неудачной валидации
        session(['form' => request()->all()]);
        $data = request()->validate([
            'name' => 'required',
            'duration' => ['min:0', 'nullable', 'numeric']
        ]); 
        $data = request()->session()->pull('form');

        Task::create([
            'name' => $data['name'],
            'type_id' => $data['type_id'],
            'duration' => is_null($data['duration']) ? 0 : $data['duration'],
            'prio' => $data['prio'],
            'is_done' => 0,
            'user_id' => auth()->id(),
            'date' => session('date')
        ]);
        return redirect()->route('tasks.show', session('date'));
    }


    public function update($date) {
        $data = request()->all();
        $task = Task::find($data['id']);

        // Если просто отмечаем сделанным
        if (array_key_exists('is_done', $data)) {
            $task->is_done = $data['is_done'] == 'on' ? 1 : 0;
        // ... или если редактируем полностью
        } else {
            $task->name = $data['name']; 
            $task-> type_id = $data['type_id'];
            $task-> duration = $data['duration'];
            $task-> prio = $data['prio'];
        }
        $task->save();
        return redirect()->route('tasks.show', ['date' => $date]);
    } 

    public function edit($date) {
        $data = request()->all();
        $task = Task::find($data['id']);
        request()->session()->flash('id', $data['id']);
        request()->session()->flash('form', [
            'name' => $task['name'],
            'type_id' => $task['type_id'],
            'duration' => $task['duration'],
            'prio' => $task['prio']
        ]);
        return redirect()->route('tasks.show', ['date' => $date]);;
    }
}
