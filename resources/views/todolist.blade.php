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
                        <div class="card-deck">
                            @if($toDoList->listItems->isEmpty())
                                <h5>No deals</h5>
                            @else
                                <ul class="list-group list-group-numbered">
                                    @foreach($toDoList->listItems as $deal)
                                        <li class="list-group-item">{{ $deal->name }}</li>
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
                            $('.list-group').append($('<li class="list-group-item">'+ dealName+'</li>'));
                        }
                        console.log(data['msg']);
                    }
                });
            }
        });
    }, 100);
</script>
