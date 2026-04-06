<?php

namespace Tests\Unit;

use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_status_accessor_returns_human_readable_label_for_each_status(): void
    {
        $waiting = new Profile(['status' => Profile::STATUS_WAITING]);
        $active = new Profile(['status' => Profile::STATUS_ACTIVE]);
        $inactive = new Profile(['status' => Profile::STATUS_INACTIVE]);

        $this->assertSame(Profile::STATUSES[Profile::STATUS_WAITING], $waiting->status);
        $this->assertSame(Profile::STATUSES[Profile::STATUS_ACTIVE], $active->status);
        $this->assertSame(Profile::STATUSES[Profile::STATUS_INACTIVE], $inactive->status);
    }

    public function test_status_constants_match_statuses_dictionary_keys(): void
    {
        $this->assertSame(
            [
                Profile::STATUS_WAITING,
                Profile::STATUS_ACTIVE,
                Profile::STATUS_INACTIVE,
            ],
            array_keys(Profile::STATUSES)
        );
    }

    public function test_store_validation_rejects_null_required_fields(): void
    {
        $validator = Validator::make([
            'firstName' => null,
            'lastName' => null,
            'image' => null,
            'status' => null,
        ], (new StoreRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('firstName', $validator->errors()->toArray());
        $this->assertArrayHasKey('lastName', $validator->errors()->toArray());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
        $this->assertArrayNotHasKey('image', $validator->errors()->toArray());
    }

    public function test_update_validation_rejects_null_fields_when_provided(): void
    {
        $validator = Validator::make([
            'firstName' => null,
            'lastName' => null,
            'image' => null,
            'status' => null,
        ], (new UpdateRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('firstName', $validator->errors()->toArray());
        $this->assertArrayHasKey('lastName', $validator->errors()->toArray());
        $this->assertArrayHasKey('image', $validator->errors()->toArray());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
    }
}
