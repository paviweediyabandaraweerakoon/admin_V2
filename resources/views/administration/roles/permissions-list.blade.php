@foreach (\App\Models\Menu::whereIsRoot()->get() as $root)
    <div class="super">
        <div class="row">
            <div class="col-md-5">
                <?php $exists = false; ?>
                @foreach($root->children()->get() as $key => $child)
                    <?php $exists = !array_diff($child->permissions()->pluck('name')->toArray(), $rolePermissions->toArray());
                        if(!$exists) {
                            $exists = false;
                            break;
                        }
                    ?>
                @endforeach
                <input type="checkbox" class="mr-1 superParentCheckBox" {{ (!empty($root->permissions()->pluck('name')->toArray()) && !array_diff($root->permissions()->pluck('name')->toArray(), $rolePermissions->toArray())) || $exists ? 'checked' : null }}>
                <label class="font-weight-bold text-right">{{ $root->title }}</label>
                @if(!empty($root->url))
                    <div class="col-md-12 ml-3 main-parent">
                        @foreach ($root->permissions()->get() as $permission)
                        <input type="checkbox" name="permissions[]"
                            {{ $rolePermissions->contains($permission->name) ? 'checked' : null }} value="{{ $permission->name }}"
                            class="mr-1 parentCheckBox"><label class="font-weight-bold">{{ $permission->name }}</label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            @foreach ($root->children()->get() as $child)
                @if (empty($child->url) && !empty($child->parent_id))
                    <div class="sub-super">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="checkbox" class="mr-1 secondParentCheckBox">
                                <label class="font-weight-bold text-right">{{ $child->title }}</label>
                            </div>
                        </div>
                        <div class="row ml-3">
                            @foreach ($child->children()->get() as $children)
                                <div class="col-md-6 main-parent">
                                    <input type="checkbox" {{ !array_diff($children->permissions()->pluck('name')->toArray(), $rolePermissions->toArray()) ? 'checked' : null }} class="mr-1 parentCheckBox"><label
                                        class="font-weight-bold">{{ $children->title }}</label>
                                    <div class="col-md-12">
                                        <ul>
                                            @foreach ($children->permissions()->get() as $permission)
                                                <li class="text-nowrap">
                                                    <input type="checkbox" name="permissions[]"
                                                        {{ $rolePermissions->contains($permission->name) ? 'checked' : null }}
                                                        value="{{ $permission->name }}"
                                                        class="mr-1 childCheckBox">{{ $permission->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="col-md-6 main-parent ml-3">
                        <input type="checkbox" {{ !array_diff($child->permissions()->pluck('name')->toArray(), $rolePermissions->toArray()) ? 'checked' : null }} class="mr-1 parentCheckBox"><label
                            class="font-weight-bold">{{ $child->title }}</label>
                        <div class="col-md-12">
                            <ul>
                                @foreach ($child->permissions()->get() as $permission)
                                    <li class="text-nowrap">
                                        <input type="checkbox" name="permissions[]"
                                            {{ $rolePermissions->contains($permission->name) ? 'checked' : null }}
                                            value="{{ $permission->name }}"
                                            class="mr-1 childCheckBox">{{ $permission->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endforeach
