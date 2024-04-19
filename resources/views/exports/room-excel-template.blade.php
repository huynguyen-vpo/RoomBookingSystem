<table>
    <thead>
    <tr>
        <th>Room Number</th>
        <th>Status</th>
        <th>Type</th>
        <th>Capacity</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rooms as $room)
        <tr>
            <td>{{ $room->room_number }}</td>
            <td>{{ $room->status }}</td>
            <td>{{ $room->type->type }}</td>
            <td>{{ $room->type->capacity }}</td>
            <td>{{ $room->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>