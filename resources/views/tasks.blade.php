<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <title>ToDo List</title>
</head>
<body>
    <div class="containter w-75 pt-3 m-auto">
        <div class="d-flex justify-content-between align-items-center">
            <div></div>
            <div class="d-flex flex-column align-items-center">
                <h1 class="day__date">{{ $date }}</h1>
                <form action="/tasks" method="get">
                    <input 
                        onChange="this.form.submit()" 
                        name="date" 
                        type="date" 
                        value={{ $date }}>
                </form>
            </div>
            @auth
                <form action="{{route('logout')}}" method="post">
                    @csrf

                    <div class="position-absolute" style="top:25px;">
                        <div class="text-right">
                            {{auth()->user()['email']}}
                        </div>
                        <input type="submit" value="Выйти" class="btn btn-danger">               
    
                    </div>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
            
        </div>
            <div class="border row mt-3">
                <div class="col"></div>
                <form class=" py-4 col-5" method="POST" action="/tasks/">
                    <h2 class="text-center">Добавить задачу</h2>
                    @csrf
                    @isset($id)
                        @method('PUT')
                        <input type="hidden" name="id" value="{{$id}} class="form-control"">
                    @endisset
                    <div class="form-group">
                        <label class="w-100">
                            Название
                            <input 
                                type="text" 
                                name='name' 
                                class="form-control"
                                @isset($form)
                                    value='{{$form['name']}}'    
                                @endisset
                                @empty($form)
                                    value=''
                                @endempty>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </label>
    
                    </div>
                    <div class="form-group">
                        <label class=" w-100">
                            Тип
                            <select 
                                name='type_id'
                                class="form-control"
                                @isset($form)
                                    value='{{$form['type_id']}}'    
                                @endisset
                                @empty($form)
                                    value='1'    
                                @endempty>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        @isset($form)
                                            @if ($form['type_id'] == $type->id)
                                                selected
                                            @endif
                                        @endisset
                                        >{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </label>    
                    </div>
                    <div class="form-group">
                        <label class=" w-100">
                            Длительность (мин.)
                            <input 
                                type="number" 
                                name="duration" 
                                class="form-control"
                                @isset($form)
                                    value="{{$form['duration']}}"
                                @endisset
                                @empty($form)
                                    value="0"
                                @endempty>
                            @error('duration')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </label>
    
                    </div>
                    <div class="form-group text-center">
                        Приоритет
                        <div class="form-check">
                            <input 
                                type="radio" 
                                name="prio" 
                                value="1"
                                class="form-check-input" 
                                id="high_prio"
                                @isset($form)
                                    @if ($form['prio'])
                                        checked
                                    @endif
                                @endisset
                                >
                            <label class="form-check-label" for="high_prio">
                                Высокий
                            </label>    
                        </div>
                        <div class="form-check">
                            <input 
                                type="radio" 
                                name="prio" 
                                value="0" 
                                class="form-check-input" 
                                id="low_prio"
                                @isset($form)
                                    @if (!$form['prio'])
                                        checked
                                    @endif
                                @endisset
                                @empty($form)
                                    checked
                                @endempty
                                >

                            <label class="form-check-label" for="low_prio">
                                Низкий
                            </label>    

                        </div>
    
                    </div>
                    <div class="text-center">
                        <input 
                        type="submit" 
                        class="btn btn-primary"
                        @isset($id)
                            value="Редактировать задачу"
                        @endisset 
                        @empty($id)
                            value="Добавить задачу"                            
                        @endempty
                        >
                        <a href="{{url()->previous()}}" class="btn btn-secondary">
                            Отмена
                        </a>

                    </div>
                </form>
                <div class="col"></div>
            </div>
            <div class="day__tasks tasks">
                <table class="tasks__table table" id="tasks-table">
                    <tr scope="row" class="tasks__tr">
                        <th></th>
                        <th>Название</th>
                        <th>Тип</th>
                        <th>Длительность (мин.)</th>
                        <th>Выполнено</th>
                        <th></th>
                    </tr>
                    @foreach ($tasks_current as $task)
                        <tr scope="row" class="tasks__tr">
                            <td class="tasks__td">
                                @if ($task->prio)
                                <span class="tasks__prio">*</span>
                                @endif
                            </td>
                            <td class="tasks__td">{{ $task->name }}</td>
                            <td class="tasks__td">{{ $task->type }}</td>
                            <td class="tasks__td">{{ $task->duration }}</td>
                            <td class="tasks__td">
                                <form method="POST" action="/tasks/{{ $date }}">
                                    @csrf
                                    @method('PUT')
                                    <input hidden name="id" value="{{ $task->id }}">
                                    <input type="hidden" name="is_done" value="off">
                                    <input type="checkbox" onChange="this.form.submit()" name="is_done">
                                </form>
                            </td>
                            <td class="tasks__td">
                                <form action="/tasks/{{ $date }}/edit" method="get">
                                    <input hidden name="id" value="{{ $task->id }}">
                                    <input type="submit" value="Ред" class="btn btn-outline-primary btn-sm"> 
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration">Общ. длительность: 
                            {{ $duration }}
                        </td>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration"></td>                        
                    </tr>
                </table>
                <hr>
                <table class="tasks__table table" id="tasks-table">
                    <tr scope="row" class="tasks__tr">
                        <th></th>
                        <th>Название</th>
                        <th>Тип</th>
                        <th>Длительность (мин.)</th>
                        <th>Выполнено</th>
                        <th></th>
                    </tr>
                    @foreach ($tasks_done as $task)
                        <tr scope="row" class="tasks__tr">
                            <td class="tasks__td">
                                @if ($task->prio)
                                <span class="tasks__prio">*</span>
                                @endif
                            </td>
                            <td class="tasks__td">{{ $task->name }}</td>
                            <td class="tasks__td">{{ $task->type }}</td>
                            <td class="tasks__td">{{ $task->duration }}</td>
                            <td class="tasks__td">
                                <form method="POST" action="/tasks/{{ $date }}">
                                    @csrf
                                    @method('PUT')
                                    <input hidden name="id" value="{{ $task->id }}">
                                    <input type="hidden" name="is_done" value="off">
                                    <input type="checkbox" onChange="this.form.submit()" name="is_done" checked>
                                </form>
                            </td>
                            <td class="tasks__td">
                                <form action="/tasks/{{ $date }}/edit" method="get">
                                    <input hidden name="id" value="{{ $task->id }}">
                                    <input type="submit" value="Ред" class="btn btn-outline-primary btn-sm"> 
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>       
    </div>


    <script src="js/main.js"></script>
</body>
</html>