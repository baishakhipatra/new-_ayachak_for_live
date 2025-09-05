<div class="card-body">
    <table class="table">
        <thead>
            <tr>
                <th>#SR</th>
                <th>Email</th>
                <th>Comment</th>
                <th>Remarks</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($franchise as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->comment}}</td>
                <td>{{$item->comment}}</td>
                <td>{{$item->created_at}}</td>
            </tr>
            @empty
            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
