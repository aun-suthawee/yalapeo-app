<h1>List Menu</h1>
<ul>
    @foreach($lists as $level1)
        <li>
            {{ $level1->id }} - {{ $level1->slug }}
            @if(count($level1->children))
                <ul>
                    @foreach($level1->children as $level2)
                        <li>
                            {{ $level2->id }} - {{ $level2->slug }}
                            @if(count($level2->children))
                                <ul>
                                    @foreach($level2->children as $level3)
                                        <li>
                                            {{ $level3->id }} - {{ $level3->slug }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>