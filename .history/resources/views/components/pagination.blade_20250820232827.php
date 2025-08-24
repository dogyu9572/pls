@props(['paginator'])

@if ($paginator->hasPages())
    <div class="board-pagination">
        <nav aria-label="페이지 네비게이션">
            <ul class="pagination justify-content-center">
                {{-- 이전 페이지 링크 --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- 페이지 번호들 --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- 다음 페이지 링크 --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>

        {{-- 페이지 정보 표시 --}}
        <div class="pagination-info text-center mt-2">
            <small class="text-muted">
                {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} / 
                총 {{ $paginator->total() }}개 항목
                ({{ $paginator->currentPage() }} / {{ $paginator->lastPage() }} 페이지)
            </small>
        </div>
    </div>
@endif
