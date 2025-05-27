@props(['errors'])

@if ($errors->any())
<div {{ $attributes }}>
<div class="bg-red-100 border-t-4 border-red-500 rounded-b text-teal-900 px-3 py-2 shadow-md" role="alert">
    <div class="flex">
        <div>
            @foreach ($errors->all() as $error)
            <p class="font-semibold text-red-500">{{ $error }}</p>
            @endforeach
        </div>
    </div>
</div>
@endif