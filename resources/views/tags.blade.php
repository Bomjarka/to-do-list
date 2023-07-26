@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Available tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-deck ">
                            @if($tags->isEmpty())
                                <h5>No tags</h5>
                            @else
                                <ul class="list-group list-group-numbered">
                                    @foreach($tags as $tag)
                                        <li class="list-group-item d-flex flex-row justify-content-between align-items-center" id="tag-item-{{ $tag->id }}"
                                            data-tag-id="{{ $tag->id }}">
                                            <span>{{ $tag->name }}</span>
                                            <i class="fa-solid fa-pencil" id="tag-edit-id-{{ $tag->id }}"></i>
                                            <i class="fa-solid fa-trash-can" id="trash-tag-id-{{ $tag->id }}"></i>
                                        </li>
                                        <label for="edit-tag-id-{{ $tag->id }}" hidden="">Edit tag:</label>
                                        <input
                                            type="text" class="form-control" id="edit-tag-id-{{ $tag->id }}"
                                            aria-describedby="basic-addon3"
                                            value="{{ $tag->name }}" hidden="">
                                        <button type="button" class="btn btn-primary edit-tag"
                                                id="edit-tag-button-{{ $tag->id }}" hidden>Save
                                        </button>
                                        <button type="button" class="btn btn-danger edit-tag"
                                                id="cancel-edit-tag-button-{{ $tag->id }}" hidden>Cancel
                                        </button>
                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="btn btn-outline-secondary mt-1" data-bs-toggle="modal"
                                    data-bs-target="#createTagModal">Create New Tag
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal add tag-->
    <div class="modal fade" id="createTagModal" tabindex="-1" aria-labelledby="createTagModalLabel"
         aria-hidden="true">
        <form data-toggle="validator" role="form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTagModalLabel">New Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="list-name">Tag name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="tag-name" aria-describedby="basic-addon3"
                                   placeholder="Some tag">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-tag">Create</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
<script type="module">
    setTimeout(function () {
        let tagId = '';
        $('.fa-trash-can').on('click', function () {
            tagId = $(this).parent().data('tag-id');
            $.ajax({
                url: '{{ route('remove-tag') }}',
                method: 'post',
                data: {
                    tagId: tagId
                },
                success: function (data) {
                    if (data['msg'] === 'OK') {
                        let tagId = data.data.tagId
                        console.log(tagId);
                        $('#trash-tag-id-' + tagId).parent().remove();
                    }
                    console.log(data['msg']);
                }
            });
        })

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
            let tagId = $(this).parent().data('tag-id');
            $(this).parent().hide();
            $('#edit-tag-id-' + tagId).removeAttr('hidden');
            $('label[for=edit-tag-id-' + tagId).removeAttr('hidden');
            $('#edit-tag-button-' + tagId).removeAttr('hidden');
            $('#cancel-edit-tag-button-' + tagId).removeAttr('hidden');
            $('#cancel-edit-tag-button-' + tagId).on('click', function () {
                $('#tag-item-' + tagId).show()
                $('#tag-tag-id-' + tagId).attr('hidden', true);
                $('label[for=edit-tag-id-' + tagId).attr('hidden', true);
                $('#edit-tag-button-' + tagId).attr('hidden', true);
                $('#cancel-edit-tag-button-' + tagId).attr('hidden', true);
            });
            $('#edit-tag-button-' + tagId).on('click', function () {
                let newName = $('#edit-tag-id-' + tagId).val();
                $.ajax({
                    url: '{{ route('update-tag') }}',
                    method: 'post',
                    data: {
                        tagId: tagId,
                        newName: newName
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            let tagId = data.data.tagId
                            let tagName = data.data.tagName
                            $('#tag-item-' + tagId + ' > span').text(tagName);
                            $('#tag-item-' + tagId).show()
                            $('#edit-tag-id-' + tagId).attr('hidden', true);
                            $('label[for=edit-tag-id-' + tagId).attr('hidden', true);
                            $('#edit-tag-button-' + tagId).attr('hidden', true);
                            $('#cancel-edit-tag-button-' + tagId).attr('hidden', true);
                        }
                        console.log(data);
                    }
                });
            });
        })
        $(".save-tag").on('click', function (e) {
            let tagName = $('#tag-name').val()
            $('#tag-name').on('change', function () {
                $(this).removeClass('border-danger')
            });
            if (tagName === '') {
                $('#tag-name').addClass('border-danger')
            } else {
                $('#tag-name').removeClass('border-danger')
                $.ajax({
                    url: '{{ route('create-tag') }}',
                    method: 'post',
                    data: {
                        tagName: tagName,
                    },
                    success: function (data) {
                        if (data['msg'] === 'OK') {
                            let tagName = data.data.tagName
                            let tagId = data.data.tagId
                            $('.list-group').append($('  <li class="list-group-item d-flex flex-row justify-content-between align-items-center" id="tag-item-' + tagId + '"data-tag-id="' + tagId + '"><span>' + tagName + '</span><i class="fa-solid fa-pencil" id="tag-edit-id-' + tagId + '"></i><i class="fa-solid fa-trash-can" id="trash-tag-' + tagId + '"></i></li>'));
                        }
                    }
                });
            }
        });

    }, 100);
</script>
