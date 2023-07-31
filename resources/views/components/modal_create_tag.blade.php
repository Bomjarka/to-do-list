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

<script type="module">
    $(".save-tag").on('click', function () {
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
</script>
