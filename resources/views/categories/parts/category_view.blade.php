@if ($category)
    <a href="#"
       class="text-muted btn btn-outline-dark">
        {{ __($category?->name ?? '') }}
    </a>
@endif
