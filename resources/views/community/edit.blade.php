
@include('layouts.navbar')

<div class="container" style="padding-top:18px">
  <h2>Edit Post</h2>
  <form action="{{ route('community.update', ['post'=>$post->id]) }}" method="POST">
    @csrf
    {{-- in our web routes update expects POST; add other hidden inputs if using REST --}}
    <textarea name="body" rows="4" style="width:100%">{{ old('body', $post->body) }}</textarea>
    <div style="margin-top:10px"><button type="submit" class="btn-ok">Save</button></div>
  </form>
</div>

@include('layouts.footer')
@endsection
