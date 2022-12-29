@props(['name'])

<span class="text-danger">
    @error($name)
        {{$message}}
    @enderror
</span>