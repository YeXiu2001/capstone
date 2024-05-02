<table class="table table-bordered">
    <thead>
        <tr>
            <th>Member</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teamMembers as $member)
            <tr>
                <td>{{ $member->member->name }}</td>
                <td>
                    <button class="btn btn-danger btn-sm del-member-btn" data-id="{{ $member->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
