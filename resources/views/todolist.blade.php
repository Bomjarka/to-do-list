@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('ToDo Lists') }}</div>
                    <div class="card-body">
                        @if($userLists->isEmpty())
                            <h2>No lists</h2>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#createListModal">Create List
                            </button>
                        @endif
                        @foreach($userLists as $userList)
                            <h2>{{ $userList->id }}</h2>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createListModalLabel">New ToDo List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="list-name">List name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="list-name" aria-describedby="basic-addon3"
                               placeholder="First list" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-list">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    setTimeout(function () {
        $(".save-list").on('click', function () {
            let listName = $('#list-name').val()
            let userId = {{ auth()->user()->id }}
            $.ajax({
                url: '{{ route('create-list') }}',         /* Куда пойдет запрос */
                method: 'get',             /* Метод передачи (post или get) */
                data: {
                    listName: listName,
                    userId: userId
                },     /* Параметры передаваемые в запросе. */
                success: function (data) {   /* функция которая будет выполнена после успешного запроса.  */
                    alert(data);            /* В переменной data содержится ответ от index.php. */
                }
            });
        })
    }, 100);


</script>
