<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\PasswordReset
 *
 * @property string $email
 * @property string $token
 * @property string|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereToken($value)
 */
	class PasswordReset extends \Eloquent {}
}

namespace App{
/**
 * App\UserResource
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string $type
 * @property int $blocked
 * @property string|null $photo_url
 * @property string|null $last_shift_start
 * @property string|null $last_shift_end
 * @property int $shift_active
 * @property string|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastShiftEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastShiftStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereShiftActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\ItemsResource
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property string $photo_url
 * @property float $price
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Items onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Items whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Items withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Items withoutTrashed()
 */
	class Items extends \Eloquent {}
}

namespace App{
/**
 * App\InvoicesResource
 *
 * @property int $id
 * @property string $state
 * @property int $meal_id
 * @property string|null $nif
 * @property string|null $name
 * @property string $date
 * @property float $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereMealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereNif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoices whereUpdatedAt($value)
 */
	class Invoices extends \Eloquent {}
}

namespace App{
/**
 * App\Invoice_items
 *
 * @property int $invoice_id
 * @property int $item_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $sub_total_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items whereSubTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice_items whereUnitPrice($value)
 */
	class Invoice_items extends \Eloquent {}
}

namespace App{
/**
 * App\OrdersResource
 *
 * @property int $id
 * @property string $state
 * @property int $item_id
 * @property int $meal_id
 * @property int|null $responsible_cook_id
 * @property string $start
 * @property string|null $end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Items $item
 * @property-read \App\Meals $meal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereMealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereResponsibleCookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Orders whereUpdatedAt($value)
 */
	class Orders extends \Eloquent {}
}

namespace App{
/**
 * App\Restaurant_tables
 *
 * @property int $table_number
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Restaurant_tables onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables whereTableNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Restaurant_tables whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Restaurant_tables withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Restaurant_tables withoutTrashed()
 */
	class Restaurant_tables extends \Eloquent {}
}

namespace App{
/**
 * App\MealsResource
 *
 * @property int $id
 * @property string $state
 * @property int $table_number
 * @property string $start
 * @property string|null $end
 * @property int $responsible_waiter_id
 * @property float $total_price_preview
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereResponsibleWaiterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereTableNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereTotalPricePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meals whereUpdatedAt($value)
 */
	class Meals extends \Eloquent {}
}

