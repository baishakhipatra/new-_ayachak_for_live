@extends('admin.layouts.app')

@section('page', 'Franchise partner')

@section('content')
<section>
    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#SR</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Comment</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->phone}}</td>
                        <td>{{$item->email}}</td>
                        <td><div class="d-block" style="max-width: 300px;">{{$item->comment}}</div></td>
                        <td>
                            <div id="commentDetail_{{ $item->id }}">{{$item->remarks}}</div>
                            @if ($item->remarks == null)
                                <a href="javascript: void(0)" onclick='addCommentFunc({{ $item->id }}, "{{ $item->remarks }}","{{ $item->email }}")' class="badge bg-success d-flex"><i class="fi fi-br-plus"></i> Remarks </a>
                            @else
                                <a href="javascript: void(0)" onclick='addCommentFunc({{ $item->id }}, "{{ $item->remarks }}","{{ $item->email }}")' class="badge bg-success d-flex"><i class="fi fi-br-edit"></i> Remarks </a>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Second group">
                                <a href="{{ route('admin.franchise.details', $item->id) }}" class="badge bg-info edit-btn d-flex"><i class="fi fi-br-eye"></i> View</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal fade" id="addCommentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-muted font-weight-normal">Add new Remarks for <span id="emailShow"></span></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newComment" action="{{ route('admin.franchise.comment.add') }}" method="POST">
                    <input type="hidden" name="commentId" value="">
                    <div class="form-group">
                        <textarea name="commentText" cols="30" rows="10" class="form-control" placeholder="Enter comment"></textarea>
                    </div>
                    <br>
                    <br>
                    <button type="submit" class="btn btn-sm btn-primary">Add Remark</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        // modal fire
        function addCommentFunc(id, comment,email) {
            $('input[name="commentId"]').val(id);
            $('textarea[name="commentText"]').val(comment);
            $('#emailShow').text(' '+email);
            $('#addCommentModal').modal('show');
        }

        // autofocus
        var commentModalEl = document.getElementById('addCommentModal')
        commentModalEl.addEventListener('shown.bs.modal', function (event) {
            $('#newComment textarea').focus();
        })

        // form submit
        $('#newComment').on('submit', (event) => {
            event.preventDefault();
            var comment = $('textarea[name="commentText"]').val();
            var id = $('input[name="commentId"]').val();

            $.ajax({
                url: '{{ route("admin.franchise.comment.add") }}',
                method : 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    comment: comment,
                    id: id
                },
                success: function(result) {
                    $('textarea[name="commentText"]').val('');
                    $('#commentDetail_'+id).text(comment);

                    // button color
                    if (result.type == "remarksExists") {
                        $('#commentDetail_'+id).parents("td").children("a").removeClass('btn-primary btn-secondary').addClass('btn-secondary').html('<i class="fi fi-br-edit"></i> Remarks');
                    } else {
                        $('#commentDetail_'+id).parents("td").children("a").removeClass('btn-primary btn-secondary').addClass('btn-primary').html('<i class="fi fi-br-plus"></i> Remarks');
                    }
                    // $('#commentDetail_'+id+' a').removeClass('btn-primary').removeClass('btn-secondary');

                    $('#addCommentModal').modal('hide');
                    //console.log(result);
                }
            });
        });
    </script>
@endsection
