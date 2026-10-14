
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Users
        |--------------------------------------------------------------------------
        */

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uuid')->unique();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->enum('gender', ['male', 'female']);
            $table->string('date_of_birth');
            $table->integer('user_type')->default(0);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Countries
        |--------------------------------------------------------------------------
        */

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->uuid('country_uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->text('description');
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Providers
        |--------------------------------------------------------------------------
        */

        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->uuid('provider_uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->text('description');
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Provinces
        |--------------------------------------------------------------------------
        */

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->uuid('province_uuid')->unique();
            $table->string('name');
            $table->string('code');

            $table->foreignUuid('country_uuid')
                ->constrained('countries', 'country_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Districts
        |--------------------------------------------------------------------------
        */

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->uuid('district_uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->float('gps_latitude');
            $table->float('gps_longitude');

            $table->foreignUuid('province_uuid')
                ->constrained('provinces', 'province_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 6. Cities
        |--------------------------------------------------------------------------
        */

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->uuid('city_uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->float('gps_latitude');
            $table->float('gps_longitude');

            $table->foreignUuid('district_uuid')
                ->constrained('districts', 'district_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 7. Chiefs
        |--------------------------------------------------------------------------
        */

        Schema::create('chiefs', function (Blueprint $table) {
            $table->id();
            $table->uuid('chief_uuid')->unique();
            $table->string('name');
            $table->string('continent');
            $table->string('code');
            $table->float('gps_latitude');
            $table->float('gps_longitude');

            $table->foreignUuid('district_uuid')
                ->constrained('districts', 'district_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 8. Headmen
        |--------------------------------------------------------------------------
        */

        Schema::create('headmen', function (Blueprint $table) {
            $table->id();
            $table->uuid('headman_uuid')->unique();
            $table->string('name');
            $table->string('village_id');
            $table->float('gps_latitude');
            $table->float('gps_longitude');

            $table->foreignUuid('chief_uuid')
                ->constrained('chiefs', 'chief_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 9. Profiles
        |--------------------------------------------------------------------------
        */

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('profile_uuid')->unique();

            $table->foreignUuid('user_uuid')
                ->unique()
                ->constrained('users', 'user_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 10. Identities
        |--------------------------------------------------------------------------
        */

        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->uuid('identity_uuid')->unique();

            $table->foreignUuid('profile_uuid')
                ->constrained('profiles', 'profile_uuid');

            $table->string('identity_type');
            $table->string('identity_number');
            $table->string('issuer');
        });

        /*
        |--------------------------------------------------------------------------
        | 11. Profile Images
        |--------------------------------------------------------------------------
        */

        Schema::create('profile_images', function (Blueprint $table) {
            $table->id();
            $table->uuid('profile_image_uuid')->unique();
            $table->string('image_path');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->foreignUuid('profile_uuid')
                ->constrained('profiles', 'profile_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 12. Addresses
        |--------------------------------------------------------------------------
        */

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->uuid('address_uuid')->unique();
            $table->enum('type', ['permanent', 'temporal']);
            $table->string('street_address');
            $table->float('gps_longitude');
            $table->float('gps_latitude');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->foreignUuid('profile_uuid')
                ->constrained('profiles', 'profile_uuid');

            $table->foreignUuid('country_uuid')
                ->constrained('countries', 'country_uuid');

            $table->foreignUuid('province_uuid')
                ->constrained('provinces', 'province_uuid');

            $table->foreignUuid('district_uuid')
                ->constrained('districts', 'district_uuid');

            $table->foreignUuid('city_uuid')
                ->nullable()
                ->constrained('cities', 'city_uuid');

            $table->foreignUuid('chief_uuid')
                ->nullable()
                ->constrained('chiefs', 'chief_uuid');

            $table->foreignUuid('headman_uuid')
                ->nullable()
                ->constrained('headmen', 'headman_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 13. Telecoms
        |--------------------------------------------------------------------------
        */

        Schema::create('telecoms', function (Blueprint $table) {
            $table->id();
            $table->uuid('telecom_uuid')->unique();
            $table->integer('contact_type');
            $table->string('contact_value');
            $table->boolean('isActive');
            $table->boolean('isPrimary');

            $table->foreignUuid('profile_uuid')
                ->constrained('profiles', 'profile_uuid');

            $table->foreignUuid('provider_uuid')
                ->constrained('providers', 'provider_uuid');
        });

        /*
        |--------------------------------------------------------------------------
        | 14. Password Reset Tokens
        |--------------------------------------------------------------------------
        */

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | 15. Sessions
        |--------------------------------------------------------------------------
        */

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('telecoms');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('profile_images');
        Schema::dropIfExists('identities');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('headmen');
        Schema::dropIfExists('chiefs');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('users');
    }
};
