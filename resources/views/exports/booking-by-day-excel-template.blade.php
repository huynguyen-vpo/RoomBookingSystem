<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Booking Date</th>
        <th>Price</th>
        <th>Room Number</th>
        <th>Checkin Date</th>
        <th>Checkout Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->booking->target->name }}</td>
            <td>{{ $booking->booking->target->email }}</td>
            <td>{{ $booking->booking_date }}</td>
            <td>{{ $booking->price_per_day }}</td>
            <td>{{ $booking->room->room_number }}</td>
            <td>{{ $booking->booking->check_in_date }}</td>
            <td>{{ $booking->booking->check_out_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>