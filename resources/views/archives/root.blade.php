<a href="{{ route('archives-page') }}?directory={{ $directory->id }}">{{ $directory->name }}
@if(!empty($directory->parent))
    @include('archives.root', ['directory' => $directory->parent])
@endif