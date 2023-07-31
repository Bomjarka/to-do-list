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

    @include('components.modal_create_tag')
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
                        $('#trash-tag-id-' + tagId).parent().remove();
                    }
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
                    }
                });
            });
        })

    }, 100);
</script>
