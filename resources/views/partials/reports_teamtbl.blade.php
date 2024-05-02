<div id="reports_teamtbl_container">
<table class="text-center table table-bordered" id="reports_team_tbl">
    <thead>
        <tr>
            <th>Team Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="reports-teamTbody">
    @forelse($rteams as $rt)
        <tr>
            <td>{{ $rt->team_name ?? 'N/A' }}</td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input status-toggle" type="checkbox" data-team-id="{{ $rt->id }}" {{ $rt->status == 'available' ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $rt->status == 'available' ? 'Available' : 'Busy' }}</label>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2">No Team Members Found</td>
        </tr>
    @endforelse
    </tbody>
</table>
<!-- Add pagination -->
<div id="reports_teamtbl_pagination">
    {{ $rteams->onEachSide(2)->links() }}
</div>
</div>
