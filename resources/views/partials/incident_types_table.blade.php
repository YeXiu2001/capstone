<div id="incident_table_container">
    <table class="text-center table table-bordered" id="incident_type_tbl">
        <thead>
            <tr>
                <th>Incident Type</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="incident_type_tbody">
            @foreach($incident_types as $incident)
                <tr>
                    <td>{{$incident->cases}}</td>
                    <td>{{$incident->createdByUser->name}}</td>
                    <td>{{$incident->updatedByUser->name}}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{$incident->id}}" data-bs-toggle="modal" data-bs-target="#edit_incident_modal">Edit</button> 
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{$incident->id}}">Delete</button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
        <div class="pagination">
            {{ $incident_types->onEachSide(2)->links() }}
        </div>
</div>