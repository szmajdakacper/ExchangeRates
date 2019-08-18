<div class="input-group col-lg-3 col-md-12 mb-3">
    <div class="input-group-prepend">
        <label class="input-group-text" for="{{$id}}">Last</label>
    </div>
    <select class="custom-select" name="{{$name}}" id="{{$id}}">
        <option value="1" @if($activePeriod == 1) selected @endif>Month</option>
        <option value="2" @if($activePeriod == 2) selected @endif>Three Month</option>
        <option value="3" @if($activePeriod == 3) selected @endif>Year</option>
    </select>
</div>