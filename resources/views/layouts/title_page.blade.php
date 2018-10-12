<div class="sectionTitle text-center">
    @if (isset($button) && $button)
    <h2 style="margin-bottom: 20px;">
        <span class="shape shape-left bg-color-4"></span>
        <span>{{ $title_page }}</span>
        <span class="shape shape-right bg-color-4"></span>
    </h2>
    <div class="page-action-content">
        @if ($button['type'] == 'button')
        <button class="btn btn-primary">{{ $button['text'] }}</button>
        @else
        <a href="{{ $button['href'] }}" class="btn btn-primary">{{ $button['text'] }}</a>
        @endif
    </div>
    @else
    <h2>
        <span class="shape shape-left bg-color-4"></span>
        <span>{{ $title_page }}</span>
        <span class="shape shape-right bg-color-4"></span>
    </h2>
    @endif
</div>
