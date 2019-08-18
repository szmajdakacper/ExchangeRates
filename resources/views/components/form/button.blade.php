<div class="input-group col-lg-12 col-md-12 p-0 my-3">
    <input type="submit" value="{{$value}}" @foreach($attributes as $key => $value) {{$key}}='{{$value}}' @endforeach>
</div>