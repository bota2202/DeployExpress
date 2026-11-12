<ul class="timeline">
    @foreach($events as $e)
        <li data-n="{{ str_pad($events->count() - $loop->index, 2, '0', STR_PAD_LEFT) }}">
            <span class="badge {{ $e->status }}">{{ \App\Models\Delivery::STATUSES[$e->status] }}</span>
            @if($e->location) <strong>{{ $e->location }}</strong>@endif
            @if($e->note) — {{ $e->note }}@endif
            <div class="when">{{ $e->created_at->format('d/m/Y H:i') }}@if(!empty($showUser)) · {{ $e->user->name }}@endif</div>
        </li>
    @endforeach
</ul>
