<div class="sectionTitle text-center">
    <h2>
        <span class="shape shape-left bg-color-4"></span>
        <span>{{ $title_page }}</span>
        <span class="shape shape-right bg-color-4"></span>
    </h2>
    @if (isset($button) && $button)
    <div class="page-action-content">
        @if ($button['type'] == 'button')
        <button class="btn btn-primary">{{ $button['text'] }}</button>
        @else
        <a href="{{ $button['href'] }}" class="btn btn-primary">{{ $button['text'] }}</a>
        @endif
    </div>
    @endif
</div>
