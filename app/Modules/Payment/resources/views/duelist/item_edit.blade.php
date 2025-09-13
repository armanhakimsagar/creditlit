<form action="{{route('due.item.update')}}" method="POST"  enctype="multipart/form-data">
    @csrf
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="e_amount" value="{{$data->amount}}" name="amount">
            <input type="hidden" name="id" value="{{$data->id}}">
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>