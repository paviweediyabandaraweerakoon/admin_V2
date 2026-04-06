<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * AMCInvoice Model
 * Represents the 'amc_invoices' table and its relationships.
 *
 * @property int $id
 * @property int $project_id
 * @property string $invoice_no
 * @property string|null $description
 * @property numeric $amount
 * @property string $status pending, paid, cancelled
 * @property \Illuminate\Support\Carbon $invoice_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \App\Models\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereInvoiceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AMCInvoice withoutTrashed()
 */
	class AMCInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property string|null $event
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $properties
 * @property string|null $batch_uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $causer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog causedByList()
 * @method static \Database\Factories\ActivityLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog filterByUser($is_admin, $user_id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog filterData($daterRange, $performed_on, $causedBy, $activity)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog performedOnList()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog searchData($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog tableData($order_column, $order_by_str, $start, $length)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * Customer Model
 * * Represents the 'customers' table.
 *
 * @property int $id
 * @property string $company_name
 * @property string|null $phone
 * @property string|null $country
 * @property bool $status 1=active, 0=inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer searchData(?string $term, array $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer tableData(string $order_column, string $order_by_str, int $start, int $length)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withoutTrashed()
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $parent_id
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $depth
 * @property string $title
 * @property string|null $url
 * @property string|null $icon
 * @property int|null $menu_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Kalnoy\Nestedset\Collection<int, Menu> $children
 * @property-read int|null $children_count
 * @property-read Menu|null $parent
 * @property-read Menu|null $parentMenu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu ancestorsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu ancestorsOf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu applyNestedSetScope(?string $table = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu countErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu d()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu defaultOrder(string $dir = 'asc')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu descendantsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu fixSubtree($root)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu fixTree($root = null)
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu getNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu getPlainNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu getTotalErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu hasChildren()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu hasParent()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu isBroken()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu leaves(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu makeGap(int $cut, int $height)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu moveNode($key, $position)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu orWhereDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu orWhereNodeBetween($values)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu orWhereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu query()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu rebuildSubtree($root, array $data, $delete = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu rebuildTree(array $data, $delete = false, $root = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu reversed()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu root(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu searchData($term)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu tableData($order_column, $order_by_str, $start, $length)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereAncestorOrSelf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereCreatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereDepth($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereIcon($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereIsAfter($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereIsBefore($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereIsLeaf()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereIsRoot()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereLft($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereMenuOrder($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereNodeBetween($values, $boolean = 'and', $not = false, $query = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereParentId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereRgt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereTitle($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereUpdatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu whereUrl($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu withDepth(string $as = 'depth')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Menu withoutRoot()
 */
	class Menu extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property int|null $menu_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission searchData($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission tableData($order_column, $order_by_str, $start, $length)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutRole($roles, $guard = null)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * Project Model
 * Represents the 'projects' table and its relationships.
 *
 * @property int $id
 * @property int $customer_id
 * @property string $project_name
 * @property string|null $description
 * @property numeric $initial_value
 * @property string $status
 * @property numeric|null $amc_percentage
 * @property int|null $amc_durations_month
 * @property \Illuminate\Support\Carbon|null $launch_date
 * @property \Illuminate\Support\Carbon|null $next_amc_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AMCInvoice> $amcInvoices
 * @property-read int|null $amc_invoices_count
 * @property-read \App\Models\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project searchData(?string $term, array $columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project tableData(string $order_column, string $order_by_str, int $start, int $length)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereAmcDurationsMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereAmcPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereInitialValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereLaunchDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereNextAmcDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project withoutTrashed()
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property int|null $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role searchData($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role tableData($order_column, $order_by_str, $start, $length)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $landing_page
 * @property string|null $remember_token
 * @property int|null $enabled
 * @property int|null $wrong_attempts
 * @property string $last_login
 * @property string $password_changed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Infinitypaul\LaravelPasswordHistoryValidation\Models\PasswordHistory> $passwordHistory
 * @property-read int|null $password_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User searchData($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User tableData($order_column, $order_by_str, $start, $length)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLandingPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWrongAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

