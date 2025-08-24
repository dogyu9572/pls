@props(['title', 'description' => null])

<div class="board-card">
    <div class="board-card-header">
        <h6>{{ $title }}</h6>
        @if($description)
            <p class="board-card-description">{{ $description }}</p>
        @endif
    </div>
    <div class="board-card-body">
        {{ $slot }}
    </div>
</div>
