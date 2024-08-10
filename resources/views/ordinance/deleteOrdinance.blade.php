<div class="modal fade" id="deleteModal{{$ord->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{$ord->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger font-weight-bold" id="deleteModalLabel{{$ord->id}}">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete Ordinance No. <strong>{{ $ord->ordinance_number }}</strong>?
            </div>
            <div class="modal-footer">
              <form action="{{ route('ordinance.destroyOrdinance', $ord->id) }}" method="POST" class="user">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-user">Confirm Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>