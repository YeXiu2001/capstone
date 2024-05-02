<div id="teams_table_container">
<table class="text-center table table-bordered" id="teams-tbl">
                            <thead>
                                <tr>
                                    <th>Team Name</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="teams-tbody">
                            @foreach($forteams as $teamtbl)
                                    <tr>
                                        <td>{{ $teamtbl->team_name ?? 'N/A' }}</td>
                                        <td>{{  App\Models\responseTeam_model::STATUS_OPTIONS[$teamtbl->status] ?? 'N/A'}}</td>
                                        <td>{{ $teamtbl->createdByUser->name ?? 'N/A' }}</td>
                                        <td>{{ $teamtbl->UpdatedByUser->name ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm view-team-btn" data-id="{{$teamtbl->id}}" data-bs-toggle="modal" data-bs-target="#viewmem_modal">View Members</button>
                                            <button class="btn btn-warning btn-sm edit-team-btn" data-id="{{$teamtbl->id}}" data-bs-toggle="modal" data-bs-target="#edit_t_modal">Edit</button> 
                                            <button class="btn btn-danger btn-sm delete-btn" id="del-team-btn" data-id="{{$teamtbl->id}}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- //add pagination -->
                        <div id="teams_pagination">
                            {{ $forteams->onEachSide(2)->links() }}
                        </div>

</div>