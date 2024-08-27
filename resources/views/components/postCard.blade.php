@props(['post', 'full' => false])

<div class="card">

  {{-- Cover photo --}}
  <div class="h-52 rounded-md mb-4 w-full object-cover overflow-hidden">
      @if ($post->image)
      <img src="{{ asset('storage/' . $post->image)}}" alt="ImagePost">
      @else
      <img src="{{ asset('storage/posts_images/default.png')}}" alt="default image">
      @endif

  </div>

  {{-- Title --}}
  <h2 class="font-bold text-xl">{{ $post->title }}</h2>

  {{-- Author and Date --}}
  <div class="text-xs font-light mb-4">
    <span>Posted {{ $post->created_at->diffForHumans() }} by </span>
    @if($post->user)
      <a href="{{ route('posts.users', $post->user) }}" class="text-blue-500 font-medium">{{ $post->user->username }}</a>
    @else
      <span class="text-gray-500">Unknown Author</span>
    @endif
  </div>

  {{-- Body --}} 
 @if ($full)
 <div class="text-sm">
  <span>{{ ($post->body) }}</span>
 </div>
 @else
 <div class="text-sm">
   <span>{{ Str::words($post->body, 15) }}</span>
   <a href="{{ route('posts.show', $post)}}" class="text-blue-500">Read  more &rarr; </a>
  </div>
 @endif

 <div class="flex items-center justify-end gap-4 mt-6 text-red-500">
  {{ $slot }}
 </div>
  
</div> 
