<div id="citizens-container">
    <table class="text-center table table-bordered" id="citizens_tbl">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="citizenstbl_tbody">
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->contact }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $user->id }}">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No users found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">
        {{ $users->onEachSide(2)->links() }}
    </div>
</div>