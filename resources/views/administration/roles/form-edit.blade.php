<div class="form-group">
    @if (isset($editable) && $editable)
        <input type="hidden" name="id" class="id">
    @endif
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Level</label>
        </div>
        <div class="col-md-9">
            <select name="level" id="level" class="form-control level" placeholder="Select a menu" required>
                <option value="" selected disabled> Select a level</option>
                @foreach($levels as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Name</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='name' class="form-control name" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Permissions</label>
        </div>
        <div class="col-md-9 ml-2 mt-5">
            <div class="" id="permissionForm"></div>
        </div>
    </div>
</div>
