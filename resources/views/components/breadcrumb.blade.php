{{-- Breadcrumb Navigation --}}
@php
    $breadcrumbs = breadcrumb()->auto()->get();
@endphp

@if($breadcrumbs && count($breadcrumbs) >= 1)
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container">        
        <ol class="breadcrumb">
            @foreach($breadcrumbs as $index => $crumb)
                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" 
                    @if($loop->last) aria-current="page" @endif>
                    @if($crumb['url'] && !$loop->last)
                        <a href="{{ $crumb['url'] }}" class="breadcrumb-link">
                            @if($loop->first)
                                <i class="fas fa-home me-1"></i>
                            @endif
                            {{ $crumb['title'] }}
                        </a>
                    @else
                        @if($loop->first)
                            <i class="fas fa-home me-1"></i>
                        @endif
                        {{ $crumb['title'] }}
                    @endif
                    
                    @if(!$loop->last)
                        <i class="fas fa-chevron-right breadcrumb-separator-icon ms-2"></i>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>
@endif
