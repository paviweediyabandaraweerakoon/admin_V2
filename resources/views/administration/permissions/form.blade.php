<div class="form-group">
    <div class="row mb-1 parentRow">
        <div class="col-md-3 text-right mt-2">
            <label>Menu</label>
        </div>
        <div class="col-md-9">
            <select name="menu_id" id="menu_id" class="form-control menu_id" placeholder="Select a menu" required>
                <option value="" selected disabled> Select a menu</option>
                @foreach($menus as $value => $label)
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
</div>
