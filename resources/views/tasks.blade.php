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
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <input type="submit" value="Выйти" class="text-sm text-gray-700 dark:text-gray-500 underline">               
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif
    <div id="app">
        <div class="day">
                <h1 class="day__date">{{ $date }}</h1>
                <form action="/tasks" method="get">
                    <input 
                        onChange="this.form.submit()" 
                        name="date" 
                        type="date" 
                        value={{ $date }}>
                </form>
            <div class="day__add-task add-task">
                <form class="add-task__form" method="POST" action="/tasks/{{$date}}">
                    @csrf
                    @isset($id)
                        @method('PUT')
                        <input type="hidden" name="id" value="{{$id}}">
                    @endisset
                    <label class="add-task__input">
                        Название;
                        <input 
                            type="text" 
                            name='name' 
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
                    <label class="add-task__input">
                        Тип:
                        <select 
                            name='type_id'
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
                    <label class="add-task__input">
                        Длительность:
                        <input 
                            type="number" 
                            name="duration" 
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
                    <div class="add-task__input">
                        Приоритет:
                        <label class="add-task__input">
                            Высокий
                            <input 
                                type="radio" 
                                name="prio" 
                                value="1"
                                @isset($form)
                                    @if ($form['prio'])
                                        checked
                                    @endif
                                @endisset
                                >
                        </label>
                        <label class="add-task__input">
                            Низкий
                            <input 
                                type="radio" 
                                name="prio" 
                                value="0" 
                                @isset($form)
                                    @if (!$form['prio'])
                                        checked
                                    @endif
                                @endisset
                                @empty($form)
                                    checked
                                @endempty
                                
                                >
                        </label>
                    </div>
                    <input 
                        type="submit" 
                        class="add-task__button"
                        @isset($id)
                            value="Редактировать задачу"
                        @endisset 
                        @empty($id)
                            value="Добавить задачу"                            
                        @endempty
                        >
                </form>
                
                <button onClick="clearForm" class="add-task__button">
                    Отмена
                </button>
                
            </div>
            <div class="day__tasks tasks">
                <table class="tasks__table" id="tasks-table">
                    @foreach ($tasks_current as $task)
                        <tr class="tasks__tr">
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
                                    <input type="submit" value="Ред"> 
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration">Длительность: 
                            {{ $duration }}
                        </td>
                        <td class="tasks__duration"></td>
                        <td class="tasks__duration"></td>                        
                    </tr>
                </table>
                <hr>
                <table class="tasks__table tasks__table_done" id="tasks-table">
                    @foreach ($tasks_done as $task)
                        <tr class="tasks__tr">
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
                                <button onClick="editTask" name="{{$task->id}}"> ... </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{--
                <table class="tasks__table tasks__done" id="tasks-table">
                    <template v-for="task in tasks">
                        <tr class="tasks__tr" v-if="task.isDone">
                            <td class="tasks__td">
                                <span class="tasks__prio" v-if="task.prio">*</span>
                            </td>
                            <td class="tasks__td">{{ task.name }}</td>
                            <td class="tasks__td">{{ task.type }}</td>
                            <td class="tasks__td">{{ task.duration }}</td>
                            <td class="tasks__td">
                                <input type="checkbox" v-model="task.isDone">
                            </td>
                        </tr>
                    </template>
                </table>--}}
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>