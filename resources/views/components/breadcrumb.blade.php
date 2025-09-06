{{-- Breadcrumb Component --}}
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <ol class="breadcrumb-list">
        @foreach($breadcrumbs as $index => $breadcrumb)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                @if(!$loop->last && isset($breadcrumb['url']))
                    <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-link">
                        @if(isset($breadcrumb['icon']))
                            <span class="breadcrumb-icon">{!! $breadcrumb['icon'] !!}</span>
                        @endif
                        {{ $breadcrumb['title'] }}
                    </a>
                @else
                    @if(isset($breadcrumb['icon']))
                        <span class="breadcrumb-icon">{!! $breadcrumb['icon'] !!}</span>
                    @endif
                    {{ $breadcrumb['title'] }}
                @endif
                
                @if(!$loop->last)
                    <span class="breadcrumb-separator">
                        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.5 1L6.5 6L1.5 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
