<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Course/Year</th>
            <th>Sport</th>
            <th>Status</th>
            <th>Awards</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($athletes as $athlete)
        <tr>
            <td>{{ $athlete->id }}</td>
            <td>{{ $athlete->full_name }}</td>
            <td>{{ $athlete->course_year }}</td>
            <td>{{ $athlete->sport->name ?? '-' }}</td>
            <td>{{ $athlete->status }}</td>
            <td>{{ $athlete->awards->pluck('title')->join(', ') }}</td>
            <td>{{ $athlete->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
