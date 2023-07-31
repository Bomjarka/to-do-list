@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>ToDo Lists</h5>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#createListModal">Create New List
                        </button>
                    </div>
                    <div class="card-body">
                        @if($userLists->isEmpty())
                            <h2>No lists</h2>
                        @endif
                        <div class="card-deck">
                            @foreach($userLists as $userList)
                                <div class="card">
                                    <div class="card-body">
                                        <a href="{{ route('todo-list', [$user, $userList]) }}"><h5
                                                class="card-title">{{ $userList->name }}</h5></a>
                                        <p class="card-text">ToDo points: {{ $userList->listItems()->count() }}</p>
                                        @if(!empty($usersAndPermissions))
                                            <h6>Shared to:</h6>
                                            @foreach($usersAndPermissions as $userId => $userPermissions)
                                                @foreach($userPermissions as $userPermission)
                                                    @if($userList->id === $userPermission['listId'])
                                                        <h6 class="card-text">Name: {{ $userPermission['userName'] }}
                                                            Permissions:
                                                            read
                                                            <strong>{{ (string) $userPermission['read'] ? 'yes' : 'no' }}</strong>
                                                            write
                                                            <strong>{{ (string) $userPermission['write'] ? 'yes' : 'no' }}</strong>
                                                        </h6>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                        <button type="button" data-list-id="{{ $userList->id }}"
                                                class="btn btn-outline-secondary share-list-button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#shareListModal">Share List
                                        </button>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">Created: {{ $userList->created_at }}</small>
                                    </div>
                                </div>
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.modal_create_list')
    @include('components.modal_share_list')
@endsection

