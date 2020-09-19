<?php

namespace Tests\Feature\Http\RecordCollectionControllerTest;

use App\Models\Record;
use App\Models\RecordCollection;
use App\Models\User;
use Illuminate\Support\Str;

class RecordCollectionControllerIndexTest extends RecordCollectionControllerTestHelper
{
    /**
     * @test
     */
    public function anyone_can_list_the_records_in_a_users_collection()
    {
        $user = User::factory()->create();
        $record = Record::factory()->create();
        RecordCollection::factory()->create([
            'record_id' => $record->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/recordcollections/' . $user->id);

        $response->assertSee($record->title);
    }

    /**
     * @test
     */
    public function user_name_slug_can_be_used_instead_of_id_when_listing_a_users_collection()
    {
        $user = User::factory()->create([
            'name' => 'Kalle Svensson',
            'slug' => Str::slug('Kalle Svensson')
        ]);
        $record = Record::factory()->create();
        RecordCollection::factory()->create([
            'record_id' => $record->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('/recordcollections/' . Str::slug($user->name));
        $response->assertSee($record->title);
    }

}
