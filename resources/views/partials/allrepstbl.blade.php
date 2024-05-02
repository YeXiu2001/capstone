<div id="reports-container">
    <table class="text-center table table-bordered" id="reports_tbl">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reporter</th>
                <th>Incident Type</th>
                <th>Lat</th>
                <th>Long</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td>{{ $report->created_at->format('d-m-Y') }}</td>
                    <td>{{ $report->reporter }}</td>
                    <td>{{ $report->modelref_incidenttype->cases ?? 'N/A' }}</td>
                    <td>{{ $report->lat }}</td>
                    <td>{{ $report->long }}</td>
                    <td>{{ $report->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No reports found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">
        {{ $reports->onEachSide(2)->links() }}
    </div>
</div>
