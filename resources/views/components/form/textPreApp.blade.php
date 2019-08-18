<div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="{{ $id }}">{{ $text }}</span>
  </div>
  <input name="{{$name}}" type="text" class="form-control" id="{{$id}}" value="{{$value}}" class="form-control" @foreach($attributes as $key => $value) {{$key}}='{{$value}}' @endforeach>
</div>