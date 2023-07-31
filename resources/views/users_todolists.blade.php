`@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class=" d-flex justify-content-between">
                            <h5>Other Users Lists</h5>
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
                                            <div class="">
                                                <h5>List name: {{ $userListPermission->list->name }}
                                                    <small class="text-muted">
                                                        Created: {{ $userListPermission->list->created_at }}</small>
                                                </h5>
                                                <button type="button" id="create-deal-{{$userListPermission->list->id}}"
                                                        class="btn btn-outline-secondary mt-1f create-deal-button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#createDealModal"
                                                        data-list-id="{{$userListPermission->list->id}}">Create New Deal
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body ">
                                            <div class="card-deck">
                                                <h5 class="card-title"></h5>
                                                <div class="card-body">
                                                    <div class="card-deck ">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Image</th>
                                                                <th scope="col">Tags</th>
                                                                @if($userListPermission->write === true)
                                                                    <th scope="col">Actions</th>
                                                                @endif
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($userListPermission->list->listItems as $toDoListItem)
                                                                <tr>
                                                                    <td>{{ $toDoListItem->name }}
                                                                    </td>
                                                                    @if($toDoListItem->image)
                                                                        <td>
                                                                            <a href="{{ url('storage/images/' . $toDoListItem->image->name) }}"
                                                                               target="_blank">
                                                                                <img
                                                                                    src="{{ url('storage/images/' . $toDoListItem->image->preview_name) }}"
                                                                                    alt="{{ $toDoListItem->image->preview_name }}"></a>
                                                                        </td>
                                                                    @else
                                                                        <td>No image</td>
                                                                    @endif
                                                                    <td>
                                                                        Tags: {{ $toDoListItem->getTags()->pluck('name')->implode(',') }}
                                                                    </td>
                                                                    @if($userListPermission->write === true)
                                                                        <td id="user-list-item-id-"
                                                                            {{$toDoListItem->id}} data-deal-id="{{$toDoListItem->id}}">
                                                                            <i class="fa-solid fa-pencil"
                                                                               id="deal-edit-id-{{ $toDoListItem->id }} data-list-id={{$userListPermission->list->id}}"></i>
                                                                            <i class="fa-solid fa-trash-can"
                                                                               id="deal-trash-id-{{ $toDoListItem->id }}"></i>
                                                                        </td>
                                                                        <label
                                                                            for="edit-deal-id-{{ $toDoListItem->id }}"
                                                                            hidden="">Edit deal:</label>
                                                                        <input
                                                                            type="text" class="form-control"
                                                                            id="edit-deal-id-{{ $toDoListItem->id }}"
                                                                            aria-describedby="basic-addon3"
                                                                            value="{{ $toDoListItem->name }}" hidden="">
                                                                        <button type="button"
                                                                                class="btn btn-primary edit-deal"
                                                                                id="edit-deal-button-{{ $toDoListItem->id }}"
                                                                                hidden>Save
                                                                        </button>
                                                                        <button type="button"
                                                                                class="btn btn-danger edit-deal"
                                                                                id="cancel-edit-deal-button-{{ $toDoListItem->id }}"
                                                                                hidden>Cancel
                                                                        </button>
                                                                        <td>
                                                                            <select
                                                                                id="tags-select-{{ $toDoListItem->id }}"
                                                                                class="form-select select-tags"
                                                                                multiple
                                                                                aria-label="multiple select example"
                                                                                data-deal-id="{{$toDoListItem->id}}">
                                                                                @foreach(App\Models\Tag::all() as $tag)
                                                                                    <option
                                                                                        value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <button type="button"
                                                                                    class="btn btn-success add-tags-to-list-item"
                                                                                    id="add-tags-{{ $toDoListItem->id }}"
                                                                                    disabled>Add tags
                                                                            </button>
                                                                            <button type="button"
                                                                                    class="btn btn-danger remove-tags-from-list-item"
                                                                                    id="remove-tags-{{ $toDoListItem->id }}"
                                                                                    disabled>Remove tags
                                                                            </button>
                                                                        </td>

                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                        </div>
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
<!-- Modal add deal-->
<div class="modal fade" id="createDealModal" tabindex="-1" aria-labelledby="createDealModalLabel"
     aria-hidden="true">
    <form data-toggle="validator" role="form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDealModalLabel">New Deal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="list-name">Deal name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="deal-name" aria-describedby="basic-addon3"
                               placeholder="Some deal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-deal" data-list-id="">Create</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="module">
    setTimeout(function () {
        $('.fa-pencil').hover(
            function () {
                $(this).addClass('fa-2xl')
            },
            function () {
                $(this).removeClass('fa-2xl')
            });

        $('.fa-image').hover(
            function () {
                $(this).addClass('fa-2xl')
            },
            function () {
                $(this).removeClass('fa-2xl')
            });

        $('.fa-trash-can').hover(
            function () {
                $(this).addClass('fa-2xl')
            },
            function () {
                $(this).removeClass('fa-2xl')
            });

        let dealId = '';
        $('.fa-trash-can').on('click', function () {
            dealId = $(this).parent().data('deal-id');
            $.ajax({
                url: '{{ route('remove-deal') }}',
                method: 'post',
                data: {
                    dealId: dealId
                },
                success: function (data) {
                    if (data['msg'] === 'OK') {
                        window.location.reload();
                    }
                }
            });
        })

        $('.fa-pencil').on('click', function () {
            let dealId = $(this).parent().data('deal-id');
            $(this).parent().hide();
            $('#edit-deal-id-' + dealId).removeAttr('hidden');
            $('label[for=edit-deal-id-' + dealId).removeAttr('hidden');
            $('#edit-deal-button-' + dealId).removeAttr('hidden');
            $('#cancel-edit-deal-button-' + dealId).removeAttr('hidden');
            $('#cancel-edit-deal-button-' + dealId).on('click', function () {
                $('#deal-item-' + dealId).show()
                $('#edit-deal-id-' + dealId).attr('hidden', true);
                $('label[for=edit-deal-id-' + dealId).attr('hidden', true);
                $('#edit-deal-button-' + dealId).attr('hidden', true);
                $('#cancel-edit-deal-button-' + dealId).attr('hidden', true);
            });
            $('#edit-deal-button-' + dealId).on('click', function () {
                let newName = $('#edit-deal-id-' + dealId).val();
                $.ajax({
                    url: '{{ route('update-deal') }}',
                    method: 'post',
                    data: {
                        dealId: dealId,
                        newName: newName
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            window.location.reload();
                        }
                    }
                });
            });
        });
        $('.create-deal-button').on('click', function () {
            $('.save-deal').data('list-id', $(this).data('list-id'))
        });
        $(".save-deal").on('click', function () {
            let dealName = $('#deal-name').val()
            let userId = {{ auth()->user()->id }};
            let listId = $(this).data('list-id');
            $('#deal-name').on('change', function () {
                $(this).removeClass('border-danger')
            });
            if (dealName === '') {
                $('#deal-name').addClass('border-danger')
            } else {
                $('#deal-name').removeClass('border-danger')
                $.ajax({
                    url: '{{ route('create-deal') }}',
                    method: 'post',
                    data: {
                        dealName: dealName,
                        listId: listId,
                        userId: userId,
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            window.location.reload();
                        }
                    }
                });
            }
        });

        var listItemId = '';
        $('.select-tags').on('change', function () {
            listItemId = $(this).data('deal-id')
            $('#add-tags-' + listItemId).removeAttr('disabled');
            $('#remove-tags-' + listItemId).removeAttr('disabled');
        });
        $('.add-tags-to-list-item').on('click', function () {
            let selectedTagIds = $('#tags-select-' + listItemId).val();
            if (selectedTagIds.length === 0) {
                alert('You didn\'t select any tag');
            } else {
                $.ajax({
                    url: '{{ route('add-item-tags') }}',
                    method: 'post',
                    data: {
                        tags: selectedTagIds,
                        listId: listItemId,
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            window.location.reload();
                        }
                    }
                });
            }
        });

        $('.remove-tags-from-list-item').on('click', function () {
            let selectedTagIds = $('#tags-select-' + listItemId).val();
            if (selectedTagIds.length === 0) {
                alert('You didn\'t select any tag');
            } else {
                $.ajax({
                    url: '{{ route('remove-item-tags') }}',
                    method: 'post',
                    data: {
                        tags: selectedTagIds,
                        listId: listItemId,
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }, 100);
</script>

