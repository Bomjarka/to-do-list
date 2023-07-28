`@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class=" d-flex justify-content-between">
                            <h5>Other Users Lists</h5>
                            {{--                            <a href="{{ route('todo-lists-index', $user) }}"><h5>Back to ToDoLists</h5></a>--}}
                        </div>
                    </div>
                    @if(empty($usersAndListsPermissions))
                        <h5>No shared lists</h5>
                    @else
                        @foreach($usersAndListsPermissions as $userId => $userListPermissions)
                            <div class="card m-2">
                                <div class="card-header">
                                    <div class=" d-flex justify-content-between">
                                        <h5>User name: {{ \App\Models\User::find($userId)->name }}</h5>
                                    </div>
                                </div>
                                @foreach($userListPermissions as $userListPermission)
                                    <div class="card m-2">
                                        <div class="card-header">
                                            <div class=" d-flex justify-content-between">
                                                <h5>List name: {{ $userListPermission->list->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="card-deck">
                                                <h5 class="card-title"></h5>
                                                <div class="card-body">
                                                    <div class="card-deck ">
                                                        <ul class="list-group list-group-numbered">
                                                            @foreach($userListPermission->list->listItems as $toDoListItem)
                                                                <li class="list-group-item d-flex flex-row align-items-center"
                                                                    id="deal-item-{{ $toDoListItem->id }}"
                                                                    data-deal-id="{{ $toDoListItem->id }}">
                                                                    <span>{{ $toDoListItem->name }}</span>
                                                                    @if($toDoListItem->image)
                                                                        <a href="{{ url('storage/images/' . $toDoListItem->image->name) }}"
                                                                           target="_blank">
                                                                            <img
                                                                                src="{{ url('storage/images/' . $toDoListItem->image->preview_name) }}"
                                                                                alt="{{ $toDoListItem->image->preview_name }}"></a>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                        </div>
                                        {{--                                        <small class="text-muted">Created: {{ $userList->created_at }}</small>--}}
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<script type="module">
    setTimeout(function () {

    }, 100);
</script>
`
