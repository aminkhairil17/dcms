<h2>Undangan Rapat</h2>

<p>Anda diundang untuk menghadiri rapat berikut:</p>

<p>
    <strong>Agenda:</strong>
    {{ $meeting->title }}
</p>
<p>
    <strong>Pembahasan:</strong>
    {{ $meeting->agenda }}
</p>
<p>
    <strong>Tanggal:</strong>
    {{ $meeting->date_time->format('d M Y H:i') }}
</p>
<p>
    <strong>Tempat:</strong>
    {{ $meeting->location ?? '-' }}
</p>

<p>Terima kasih.</p>
