@extends('admin.layouts.app')

@section('page', 'Mail')

@section('content')
<section>
    <div class="row">
        <div class="col-lg-12">
            
            <table class="table">
                <thead>
                    <tr>
                        <th>#SR</th>
                        <th>Email</th>
                        <th>Comment</th>
                        <th class="text-center">Subscription Count</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$item->email}}</td>
                        <td>
                            <div id="commentDetail_{{ $item->id }}">{{$item->comment}}</div>
                            @if ($item->comment == null)
                                <a href="javascript: void(0)" onclick='addCommentFunc({{ $item->id }}, "{{ $item->comment }}","{{ $item->email }}")' class="badge bg-info"><i class="fi fi-br-plus"></i> Comment </a>
                            @else
                                <a href="javascript: void(0)" onclick='addCommentFunc({{ $item->id }}, "{{ $item->comment }}","{{ $item->email }}")' class="badge bg-secondary"><i class="fi fi-br-edit"></i> Comment </a>
                            @endif
                        </td>
                        <td class="text-center">{{$item->count}}</td>
                        <td>{{$item->created_at}}</td>
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
                <h5 class="text-muted font-weight-normal">Add new Comment for <span id="emailShow"></span></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newComment" action="{{ route('admin.subscription.mail.comment.add') }}" method="POST">
                    <input type="hidden" name="commentId" value="">
                    <div class="form-group">
                        <textarea name="commentText" cols="30" rows="10" class="form-control" placeholder="Enter comment"></textarea>
                    </div>

                    <br>
                    <br>
                    <button type="submit" class="btn btn-sm btn-primary">Add Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        // modal fire
        function addCommentFunc(id, comment, email) {
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
                url: '{{ route("admin.subscription.mail.comment.add") }}',
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
                    if (result.type == "commentExists") {
                        $('#commentDetail_'+id).parents("td").children("a").removeClass('btn-primary btn-secondary').addClass('btn-secondary').html('<i class="fi fi-br-edit"></i> Comment');
                    } else {
                        $('#commentDetail_'+id).parents("td").children("a").removeClass('btn-primary btn-secondary').addClass('btn-primary').html('<i class="fi fi-br-plus"></i> Comment');
                    }
                    // $('#commentDetail_'+id+' a').removeClass('btn-primary').removeClass('btn-secondary');

                    $('#addCommentModal').modal('hide');
                    //console.log(result);
                }
            });
        });
    </script>
@endsection
