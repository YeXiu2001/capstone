<div id="reports-container">
    <table class="text-center table table-bordered" id="reports_tbl">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reporter</th>
                <th>Incident Type</th>
                <th>Event Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td>{{ $report->created_at->format('d-m-Y') }}</td>
                    <td>{{ $report->reporter }}</td>
                    <td>{{ $report->modelref_incidenttype->cases ?? 'N/A' }}</td>
                    <td>{{ $report->eventdesc ?? 'No Description' }}</td>
                    <td>
                        <select class="form-control change-status" data-report-id="{{ $report->id }}">
                            @foreach(\App\Models\incident_reports::INCIDENT_STATUS as $key => $status)
                                <option value="{{ $key }}" {{ $report->status == $key ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No reports found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">
        {{ $reports->onEachSide(2)->links() }}
    </div>
</div>
