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
                                    @foreach($toDoList->listItems->sortBy('created_at') as $toDoListItem)
                                        <li class="list-group-item d-flex flex-row justify-content-between align-items-center" id="deal-item-{{ $toDoListItem->id }}"
                                            data-deal-id="{{ $toDoListItem->id }}">
                                            <span>{{ $toDoListItem->name }}</span>
                                            <i class="fa-solid fa-pencil" id="deal-edit-id-{{ $toDoListItem->id }}"></i>
                                            <i class="fa-solid fa-trash-can" id="deal-trash-id-{{ $toDoListItem->id }}"></i>
                                            @if($toDoListItem->image)
                                                <a href="{{ url('storage/images/' . $toDoListItem->image->name) }}"
                                                   target="_blank">
                                                    <img src="{{ url('storage/images/' . $toDoListItem->image->preview_name) }}"
                                                         alt="{{ $toDoListItem->image->preview_name }}"></a>

                                            @endif
                                            <i class="fa-solid fa-image" id="deal-image-id-{{ $toDoListItem->id }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#addImageModal"></i>
                                            <h5><i class="fa-solid fa-hashtag"></i>Tags: {{ $toDoListItem->tags() }}</h5>
                                        </li>
                                        <label for="edit-deal-id-{{ $toDoListItem->id }}" hidden="">Edit deal:</label>
                                        <input
                                            type="text" class="form-control" id="edit-deal-id-{{ $toDoListItem->id }}"
                                            aria-describedby="basic-addon3"
                                            value="{{ $toDoListItem->name }}" hidden="">
                                        <button type="button" class="btn btn-primary edit-deal"
                                                id="edit-deal-button-{{ $toDoListItem->id }}" hidden>Save
                                        </button>
                                        <button type="button" class="btn btn-danger edit-deal"
                                                id="cancel-edit-deal-button-{{ $toDoListItem->id }}" hidden>Cancel
                                        </button>
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
                        <button type="button" class="btn btn-primary save-deal">Create</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal add image-->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel"
         aria-hidden="true">
        <form data-toggle="validator" role="form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addImageModalLabel">Uploading image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="file-drop-area">
                            <span class="choose-file-button">Choose image:</span>
                            <span class="file-message">or drag and drop file here</span>
                            <input type="file" class="file-input" accept=".jpg,.jpeg,.png">
                        </div>
                        <div id="divImageMediaPreview">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary add-image">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
<script type="module">
    setTimeout(function () {
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
                        let dealId = data.data.dealId
                        $('#deal-trash-id-' + dealId).parent().remove();
                    }
                    console.log(data['msg']);
                }
            });
        })
        $('.fa-image').on('click', function () {
            dealId = $(this).parent().data('deal-id');
        })

        $('.file-input').on('change', function () {
            let data = '';
            if (typeof (FileReader) != "undefined") {
                let dvPreview = $("#divImageMediaPreview");
                dvPreview.html("");
                $($(this)[0].files).each(function () {
                    let file = $(this);
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let img = $("<img />");
                        img.attr("style", "width: 150px; height:100px; padding: 10px");
                        img.attr("src", e.target.result);
                        dvPreview.append(img);
                        data = {'file': reader.result};
                    }
                    reader.readAsDataURL(file[0]);
                });
            } else {
                alert("This browser does not support HTML5 FileReader.");
            }
            $('.add-image').on('click', function () {
                $.ajax({
                    url: '{{ route('add-image') }}',
                    method: 'post',
                    data: {
                        fileData: data,
                        dealId: dealId
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            window.location.reload();
                        }
                    }
                });
            });
        });

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
                            $('.list-group').append($('<li class="list-group-item">' + dealName + '<i class="fa-solid fa-pencil" id="deal-id-' + dealId + '" data-deal-id="' + dealId + '"></i><i class="fa-solid fa-trash-can" id="deal-id-' + dealId + '" data-deal-id="' + dealId + '"></i></li><i class="fa-solid fa-image" id="deal-id-' + dealId + '"data-deal-id="' + dealId + '"data-bs-toggle="modal"data-bs-target="#addImageModal"></i>'));
                        }
                        console.log(data['msg']);
                    }
                });
            }
        });

    }, 100);
</script>
