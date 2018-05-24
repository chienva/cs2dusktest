<p>Hello Word</p>
{{--<p>{{ $getCategoryLarges->category_l_alias }}</p>--}}
@foreach ($getCategoryLarges as $row)
    <p>This is user {{ $row->title }}</p>
@endforeach
