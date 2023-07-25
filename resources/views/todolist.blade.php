@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>{{ $toDoList->name }}</h5>
                        <a href="{{ route('todo-lists-index') }}"><h5>Back to ToDoLists</h5></a>
                    </div>
                    <div class="card-body">
                        <div class="card-deck ">
                            @if($toDoList->listItems->isEmpty())
                                <h5>No deals</h5>
                            @else
                                <ul class="list-group list-group-numbered">
                                    @foreach($toDoList->listItems as $deal)
                                        <li class="list-group-item" id="deal-item-{{ $deal->id }}">
                                            <span>{{ $deal->name }}</span>
                                            <i class="fa-solid fa-pencil" id="deal-id-{{ $deal->id }}"
                                               data-deal-id="{{ $deal->id }}"></i>
                                            <i class="fa-solid fa-trash-can" id="deal-id-{{ $deal->id }}"
                                               data-deal-id="{{ $deal->id }}"></i>
                                        </li>
                                        <label for="edit-deal-id-{{ $deal->id }}" hidden="">Edit deal:</label>
                                        <input
                                            type="text" class="form-control" id="edit-deal-id-{{ $deal->id }}"
                                            aria-describedby="basic-addon3"
                                            value="{{ $deal->name }}" hidden="">
                                        <button type="button" class="btn btn-primary edit-deal" id="edit-deal-button-{{ $deal->id }}" hidden>Save</button>
                                        <button type="button" class="btn btn-danger edit-deal" id="cancel-edit-deal-button-{{ $deal->id }}" hidden>Cancel</button>

                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="btn btn-outline-secondary mt-1" data-bs-toggle="modal"
                                    data-bs-target="#createDealModal">Create New Deal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createDealModal" tabindex="-1" aria-labelledby="createDealModalLabel"
         aria-hidden="true">
        <form data-toggle="validator" role="form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createListModalLabel">New Deal</h5>
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
                        <button type="button" class="btn btn-primary save-deal">Create</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
<script type="module">
    setTimeout(function () {
        $('.fa-pencil').hover(
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

        $('.fa-pencil').on('click', function () {
            let dealId = $(this).data('deal-id');
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
                            let dealId = data.data.dealId
                            let dealName = data.data.dealName
                            $('#deal-item-' + dealId + ' > span').text(dealName);
                            $('#deal-item-' + dealId).show()
                            $('#edit-deal-id-' + dealId).attr('hidden', true);
                            $('label[for=edit-deal-id-' + dealId).attr('hidden', true);
                            $('#edit-deal-button-' + dealId).attr('hidden', true);
                            $('#cancel-edit-deal-button-' + dealId).attr('hidden', true);
                        }
                        console.log(data);
                    }
                });
            });
        })
        $('.fa-trash-can').on('click', function () {
            let dealId = $(this).data('deal-id');
            $.ajax({
                url: '{{ route('remove-deal') }}',
                method: 'post',
                data: {
                    dealId: {{ $deal->id }}
                },
                success: function (data) {
                    if (data['msg'] === 'OK') {
                        let dealId = data.data.dealId
                        $('#deal-id-' + dealId).parent().remove();
                    }
                    console.log(data['msg']);
                }
            });
        })
        $(".save-deal").on('click', function (e) {
            let dealName = $('#deal-name').val()
            let userId = {{ auth()->user()->id }};
            let listId = {{ $toDoList->id }}

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
                            let dealName = data.data.dealName
                            let dealId = data.data.dealId
                            // $('.list-group').append($('<li class="list-group-item">' + dealName + '</li>'));
                            $('.list-group').append($('<li class="list-group-item">' + dealName + '<i class="fa-solid fa-pencil" id="deal-id-' + dealId + '" data-deal-id="' + dealId + '"></i><i class="fa-solid fa-trash-can" id="deal-id-' + dealId + '" data-deal-id="' + dealId + '"></i></li>'));
                        }
                        console.log(data['msg']);
                    }
                });
            }
        });
    }, 100);
</script>
