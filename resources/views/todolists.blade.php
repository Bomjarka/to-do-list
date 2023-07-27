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
    <!-- Modal Create List-->
    <div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createListModalLabel">New ToDo List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="list-name">List name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control required" id="list-name"
                                   aria-describedby="basic-addon3"
                                   placeholder="First list">
                        </div>
                        <label>Deals:</label>
                        <div class="deals-list input-group-append md-3">
                            <input type="text" class="deal form-control mb-1" id="deal-name"
                                   aria-describedby="basic-addon3"
                                   placeholder="First deal">
                        </div>
                        <button type="button" class="btn btn-info btn-sm add-deal mt-1">Add deal</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-list">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Share List-->
    <div class="modal fade" id="shareListModal" tabindex="-1" aria-labelledby="shareListModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('share-list', [$user]) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareListModalLabel">Share List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="list-name">Select users</label>
                            <select id="tags-select-"
                                    class="form-select select-tags"
                                    multiple aria-label="multiple select example"
                                    name="selected-users[]" required>
                                @foreach(\App\Models\User::all() as $user)
                                    @if($user->id !== auth()->user()->id)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <input type="hidden" id="share-list-id-input" name="list-id" value="">
                            <div class="form-check" id="read-list-check">
                                <input class="form-check-input" type="checkbox" value="true" id="read-list-checkbox"
                                       name="read-list" checked>
                                <label class="form-check-label" for="read-list-checkbox">
                                    Read list
                                </label>
                            </div>
                            <div class="form-check" id="write-list-check">
                                <input class="form-check-input" type="checkbox" value="true" id="write-list-checkbox"
                                       name="write-list">
                                <label class="form-check-label" for="write-list-checkbox">
                                    Edit list
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary share-list">Share</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script type="module">
    setTimeout(function () {
        let counter = 1

        $('.share-list-button').on('click', function () {
            $('#share-list-id-input').val($(this).data('list-id'));
        });

        $('#shareListModal').on('submit', function (event) {
            if ($('#read-list-checkbox')[0].checked === false && $('#write-list-checkbox')[0].checked === false) {
                alert('You need to set at least one permission to share list');
                event.preventDefault();
            }
        });

        $('#list-name').on('change', function () {
            $(this).removeClass('border-danger')
        });

        $(".save-list").on('click', function () {
            let listName = $('#list-name').val()
            let userId = {{ auth()->user()->id }};
            let deals = [];

            if (listName === '') {
                $('#list-name').addClass('border-danger')
            } else {
                $('#list-name').removeClass('border-danger')
            }
            $('.deal').each(function () {
                if ($(this).val() !== '') {
                    deals.push($(this).val())
                }
            });
            if (deals.length == 0) {
                $('.deal').addClass('border-danger')
            }
            if (listName !== '' && deals.length > 0) {
                $.ajax({
                    url: '{{ route('create-list') }}',
                    method: 'post',
                    data: {
                        listName: listName,
                        userId: userId,
                        deals: deals
                    },
                    success: function (data) {
                        console.log(data);
                        let listId = data.data.listId
                        let listName = data.data.listName
                        let listCreated = data.data.created
                        let dealsCount = data.data.dealsCount
                        let route = window.location.href + '/' + listId

                        $('.card-deck').append($('' +
                            '<div class="card"><' +
                            'div class="card-body">' +
                            '<a href="' + route + '"><h5 class="card-title">' + listName + '</h5></a>' +
                            '<p class="card-text">ToDo points: ' + dealsCount + '</p>' +
                            '</div><div class="card-footer"><small class="text-muted">Created: ' + listCreated + '</small></div></div><br>'));

                        //modal() is not a function, разобраться с порядком загрузки скриптов
                        // $('#createListModal').modal('hide');
                    }
                });
            }
        });

        $(".add-deal").on('click', function () {
            $('.deals-list').append($('<input type="text" class="deal form-control mb-1" id="deal-name-' + counter + '" aria-describedby="basic-addon3" placeholder="Some deal" required>'));
            counter += 1
        });
    }, 100);
</script>
