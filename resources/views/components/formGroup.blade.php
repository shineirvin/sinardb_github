@foreach($formArray as $value)
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> {{ $value['title'] }}<span class="required">*</span> </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12" value="{{ $value['value'] or '' }}" {{ $value['readonly'] or '' }}>
            <small> {{ $value['description'] or '' }} </small>
        </div>
    </div>
@endforeach