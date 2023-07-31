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

<script type="module">
    $('.share-list-button').on('click', function () {
        $('#share-list-id-input').val($(this).data('list-id'));
    });

    $('#shareListModal').on('submit', function (event) {
        if ($('#read-list-checkbox')[0].checked === false && $('#write-list-checkbox')[0].checked === false) {
            alert('You need to set at least one permission to share list');
            event.preventDefault();
        }
    });
</script>
