<div class="flex flex-row flex-nowrap justify-start gap-x-2">
    @foreach ($categories as $category)
    <div class="p-1 flex justify-start items-center">
        @if(file_exists(public_path('storage/' .$category->image )) && $category->image != null)
            <img class="aspect-square object-contain w-14" src="{{ '/storage/' . $category->image }}" alt="{{ $category->name }}">
        @else
            <img class="aspect-square object-contain w-14" src="/storage/default.jpg" alt="">
        @endif
        <div class="ml-2 tg-text-color text-xs whitespace-nowrap">{{ $category->name }}</div>
    </div>
    @endforeach
</div>
