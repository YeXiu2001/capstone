<div id="manageusers_table_container">
<table class="text-center table table-bordered" id="manageusers_tbl">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="incident_type_tbody">
                                @forelse($pending_users as $pu)
                                    <tr>
                                        <td>{{$pu->name}}</td>
                                        <td>{{$pu->email}}</td>
                                        <td>{{$pu->contact}}</td>
                                        <td>
                                            <button class="btn btn-secondary btn-sm view-btn" data-id="{{$pu->id}}" data-bs-toggle="modal" data-bs-target="#view_user_modal">View Details</button>
                                            <button class="btn btn-primary btn-sm approve-btn" data-id="{{$pu->id}}" >Approve</button> 
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{$pu->id}}">Reject</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No pending users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="pagination">
                            {{ $pending_users->onEachSide(2)->links() }}
                        </div>
</div>