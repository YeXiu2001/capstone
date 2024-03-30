<div id="reports_teamtbl_container">
<table class="text-center table" id="reports_team_tbl">
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
                                        <td>{{ $rt->status ?? 'N/A' }}</td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No Team Members Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- //add pagination -->
                        <div id="reports_teamtbl_pagination">
                            {{ $rteams->onEachSide(2)->links() }}
                        </div>
</div>