<div id="RTmem_table_container">
<table class="text-center table table-bordered" id="responseTeams_tbl">
                            <thead>
                                <tr>
                                    <th>Team Name</th>
                                    <th>Members</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="rtmem-tbody">
                            @foreach($rtmems as $rtMember)
                                    <tr>
                                        <td>{{ $rtMember->teamRefTeams->team_name ?? 'N/A' }}</td>
                                        <td>{{ $rtMember->member->name ?? 'N/A' }}</td>
                                        <td>{{ $rtMember->status }}</td>
                                        <td>{{ $rtMember->createdByUser->name ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm edit-btn" data-id="{{$rtMember->id}}" data-bs-toggle="modal" data-bs-target="#edit_rtmem_modal">Edit</button> 
                                            <button class="btn btn-danger btn-sm delete-btn" id="del-teammem" data-id="{{$rtMember->id}}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- //add pagination -->
                        <div id="rtmems_pagination">
                            {{ $rtmems->onEachSide(2)->links() }}
                        </div>
</div>