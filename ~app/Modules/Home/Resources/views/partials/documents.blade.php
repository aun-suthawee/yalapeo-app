<div class="list-group text-start">
    @php
        $groupKey = '';
        $documentGroups = config('home.documents');
        
        foreach ($documentGroups as $key => $value) {
            if (strpos($title, $key) !== false) {
                $groupKey = $key;
                break;
            }
        }
        
        $docs = [];
        $moreDocs = [];
        
        if (!empty($groupKey) && isset($documentGroups[$groupKey])) {
            $docs = $documentGroups[$groupKey]['documents'] ?? [];
            $moreDocs = $documentGroups[$groupKey]['moreDocuments'] ?? [];
        }
    @endphp

    <h6 class="text-start fw-bold mb-3">
        {{ empty($groupKey) ? 'กฎหมายและระเบียบที่เกี่ยวข้อง' : $groupKey }}
    </h6>

    @if (count($docs) > 0)
        @foreach ($docs as $doc)
            <a href="{{ $doc['url'] }}" class="list-group-item list-group-item-action small py-2" target="_blank" rel="noopener noreferrer">
                {{ $doc['title'] }}
            </a>
        @endforeach
    @else
        <p class="text-muted small">ไม่พบเอกสารที่เกี่ยวข้อง</p>
    @endif

    @if (count($moreDocs) > 0)
        <button class="btn btn-sm btn-outline-primary mt-2" type="button" 
            data-bs-toggle="collapse" data-bs-target="#morePersonnelDocs{{ $index }}"
            aria-expanded="false" aria-controls="morePersonnelDocs{{ $index }}">
            เอกสารเพิ่มเติม <i class="fas fa-chevron-down"></i>
        </button>

        <div class="collapse" id="morePersonnelDocs{{ $index }}">
            <div class="list-group text-start mt-2">
                @foreach ($moreDocs as $doc)
                    <a href="{{ $doc['url'] }}" class="list-group-item list-group-item-action small py-2" 
                       target="_blank" rel="noopener noreferrer">
                        {{ $doc['title'] }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>