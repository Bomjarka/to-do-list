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
                    <button type="button" class="btn btn-primary save-deal" data-list-id={{$toDoList->id}}>Create</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="module">
    $(".save-deal").on('click', function (e) {
        let dealName = $('#deal-name').val()
        let userId = {{ auth()->user()->id }};
        let listId = $(this).data('list-id')

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
                        $('.list-group').append($('<li class="list-group-item">' + dealName + '<i class="fa-solid fa-pencil" id="deal-id-' + dealId + '" data-deal-id="' + dealId + '"></i><i class="fa-solid fa-trash-can" id="deal-id-' + dealId + '" data-deal-id="' + dealId + '"></i></li><i class="fa-solid fa-image" id="deal-id-' + dealId + '"data-deal-id="' + dealId + '"data-bs-toggle="modal"data-bs-target="#addImageModal"></i>'));
                    }
                }
            });
        }
    });
</script>
